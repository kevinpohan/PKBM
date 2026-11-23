<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Pelatihan;
use App\Models\Pendaftaran;
use App\Models\Presensi;
use App\Models\Sesi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SesiController extends Controller
{
    public function create(?Pelatihan $pelatihan = null)
    {
        $pelatihans = Pelatihan::where('status', 'aktif')->orderBy('nama')->get();

        return view('tutor.sesi.create', compact('pelatihans', 'pelatihan'));
    }

    public function store(Request $request, ?Pelatihan $pelatihan = null)
    {
        $pelatihanId = $pelatihan ? $pelatihan->id : $request->input('pelatihan_id');

        $validated = $request->validate([
            'pelatihan_id' => $pelatihan ? 'nullable' : 'required|exists:pelatihans,id',
            'judul_sesi' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'batas_terlambat_menit' => 'nullable|integer|min:0|max:180',
            'lokasi' => 'required|string|max:255',
        ]);

        $validated['pelatihan_id'] = $pelatihanId;
        $validated['batas_terlambat_menit'] = $validated['batas_terlambat_menit'] ?? 15;
        $validated['qr_token'] = Str::random(40);
        $validated['kode_manual'] = strtoupper(Str::random(6));
        $validated['token_expired_at'] = now()->addMinutes(5);
        $validated['is_finalized'] = false;

        $sesi = Sesi::create($validated);

        return redirect()->route('tutor.pelatihan.show', $pelatihanId)
            ->with('success', "Sesi '{$sesi->judul_sesi}' berhasil dibuat!");
    }

    public function edit(Sesi $sesi)
    {
        $pelatihans = Pelatihan::where('status', 'aktif')->orderBy('nama')->get();

        return view('tutor.sesi.edit', compact('sesi', 'pelatihans'));
    }

    public function update(Request $request, Sesi $sesi)
    {
        $validated = $request->validate([
            'pelatihan_id' => 'nullable|exists:pelatihans,id',
            'judul_sesi' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'batas_terlambat_menit' => 'nullable|integer|min:0|max:180',
            'lokasi' => 'required|string|max:255',
        ]);

        $validated['pelatihan_id'] = $request->input('pelatihan_id', $sesi->pelatihan_id) ?: $sesi->pelatihan_id;
        $validated['batas_terlambat_menit'] = $validated['batas_terlambat_menit'] ?? 15;

        $sesi->update($validated);

        return redirect()->route('tutor.pelatihan.show', $sesi->pelatihan_id)
            ->with('success', "Sesi '{$sesi->judul_sesi}' berhasil diperbarui!");
    }

    public function destroy(Sesi $sesi)
    {
        $pelatihanId = $sesi->pelatihan_id;
        $judul = $sesi->judul_sesi;
        $sesi->delete();

        return redirect()->route('tutor.pelatihan.show', $pelatihanId)
            ->with('success', "Sesi '{$judul}' berhasil dihapus.");
    }

    /**
     * Buka presensi sesi langsung ke waktu sekarang
     */
    public function activateNow(Sesi $sesi)
    {
        $sesi->bukaSekarang();

        return back()->with('success', "Presensi Sesi '{$sesi->judul_sesi}' berhasil dibuka sekarang! Jam sesi diperbarui menjadi ".substr((string) $sesi->jam_mulai, 0, 5).' - '.substr((string) $sesi->jam_selesai, 0, 5).' WIB.');
    }

    /**
     * FR-10: Tutup sesi secara manual & catat otomatis status Alpa bagi peserta yang belum absen
     */
    public function finalize(Sesi $sesi)
    {
        $count = $sesi->finalizeAlpa(true);

        return back()->with('success', "Sesi '{$sesi->judul_sesi}' berhasil ditutup & difinalisasi! Sebanyak {$count} warga belajar otomatis dicatat Alpa.");
    }

    /**
     * FR-11: Halaman QR Fullscreen dengan rotasi tiap 5 menit + grace period 15 detik
     */
    public function qrFullscreen(Sesi $sesi)
    {
        $sesi->load('pelatihan.tutor');
        $sesi->rotateTokenIfNeeded();

        $qrUrl = $sesi->qr_url;
        $qrSvg = QrCode::size(340)->generate($qrUrl);
        $remainingSeconds = $sesi->remainingSeconds();
        $isWithinWindow = $sesi->isWithinAttendanceWindow();
        $liveData = $this->getLiveDataArray($sesi);

        return view('tutor.sesi.qr', array_merge(
            compact('sesi', 'qrSvg', 'qrUrl', 'remainingSeconds', 'isWithinWindow'),
            $liveData
        ));
    }

    /**
     * API JSON untuk memeriksa & auto-rotate token Sesi
     */
    public function tokenStatus(Sesi $sesi)
    {
        $rotated = $sesi->rotateTokenIfNeeded();
        $remainingSeconds = $sesi->remainingSeconds();

        $qrUrl = $sesi->qr_url;
        $qrSvg = QrCode::size(340)->generate($qrUrl);

        return response()->json([
            'rotated' => $rotated,
            'qr_token' => $sesi->qr_token,
            'kode_manual' => $sesi->kode_manual,
            'qr_url' => $qrUrl,
            'qr_svg' => (string) $qrSvg,
            'remaining_seconds' => $remainingSeconds,
            'is_within_window' => $sesi->isWithinAttendanceWindow(),
            'expired_at' => $sesi->token_expired_at ? $sesi->token_expired_at->toIso8601String() : null,
        ]);
    }

    /**
     * Endpoint untuk memaksa rotasi token sekarang
     */
    public function rotateToken(Sesi $sesi)
    {
        $sesi->rotateTokenIfNeeded(true);
        $qrUrl = $sesi->qr_url;
        $qrSvg = QrCode::size(340)->generate($qrUrl);

        return response()->json([
            'success' => true,
            'qr_token' => $sesi->qr_token,
            'kode_manual' => $sesi->kode_manual,
            'qr_url' => $qrUrl,
            'qr_svg' => (string) $qrSvg,
            'remaining_seconds' => $sesi->remainingSeconds(),
        ]);
    }

    /**
     * FR-12: Halaman Pemantauan Live Presensi
     */
    public function live(Sesi $sesi)
    {
        $sesi->load(['pelatihan.tutor']);

        $data = $this->getLiveDataArray($sesi);

        return view('tutor.sesi.live', array_merge(['sesi' => $sesi], $data));
    }

    /**
     * API JSON untuk live polling data kehadiran
     */
    public function liveData(Sesi $sesi)
    {
        $data = $this->getLiveDataArray($sesi);

        return response()->json($data);
    }

    private function getLiveDataArray(Sesi $sesi): array
    {
        // Jika sesi sudah melewati waktu selesai dan belum difinalisasi, jalankan finalisasi otomatis (FR-10)
        if (! $sesi->is_finalized && now()->greaterThanOrEqualTo($sesi->waktu_selesai)) {
            $sesi->finalizeAlpa();
            $sesi->refresh();
        }

        // Ambil seluruh peserta terdaftar yang disetujui pada pelatihan
        $approvedPesertas = Pendaftaran::with('user')
            ->where('pelatihan_id', $sesi->pelatihan_id)
            ->where('status', 'disetujui')
            ->get()
            ->pluck('user');

        // Ambil presensi untuk sesi ini
        $presensis = Presensi::where('sesi_id', $sesi->id)->get()->keyBy('user_id');

        $attendees = [];
        $countHadir = 0;
        $countTerlambat = 0;
        $countIzin = 0;
        $countSakit = 0;
        $countAlpa = 0;
        $countBelumAbsen = 0;

        foreach ($approvedPesertas as $user) {
            $presensi = $presensis->get($user->id);
            $status = $presensi ? $presensi->status : 'belum_absen';

            if ($status === 'hadir') {
                $countHadir++;
            } elseif ($status === 'terlambat') {
                $countTerlambat++;
            } elseif ($status === 'izin') {
                $countIzin++;
            } elseif ($status === 'sakit') {
                $countSakit++;
            } elseif ($status === 'alpa') {
                $countAlpa++;
            } else {
                $countBelumAbsen++;
            }

            $attendees[] = [
                'user_id' => $user->id,
                'nama' => $user->nama,
                'nomor_induk' => $user->nomor_induk ?? '-',
                'no_hp' => $user->no_hp ?? '-',
                'status' => $status,
                'metode' => $presensi ? $presensi->metode : '-',
                'waktu' => $presensi ? $presensi->waktu_server->format('H:i:s') : '-',
                'presensi_id' => $presensi ? $presensi->id : null,
            ];
        }

        $totalEnrolled = $approvedPesertas->count();
        $totalMasuk = $countHadir + $countTerlambat;
        $ratio = $totalEnrolled > 0 ? round(($totalMasuk / $totalEnrolled) * 100, 1) : 0;

        return [
            'total_enrolled' => $totalEnrolled,
            'count_hadir' => $countHadir,
            'count_terlambat' => $countTerlambat,
            'count_izin' => $countIzin,
            'count_sakit' => $countSakit,
            'count_alpa' => $countAlpa,
            'count_belum_absen' => $countBelumAbsen,
            'total_masuk' => $totalMasuk,
            'ratio' => $ratio,
            'attendees' => $attendees,
            'last_updated' => now()->format('H:i:s'),
            'is_finalized' => (bool) $sesi->is_finalized,
        ];
    }
}
