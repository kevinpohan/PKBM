<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Pelatihan;
use App\Models\Pendaftaran;
use App\Models\Presensi;
use App\Models\Sesi;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SesiController extends Controller
{
    public function create(Pelatihan $pelatihan)
    {
        return view('tutor.sesi.create', compact('pelatihan'));
    }

    public function store(Request $request, Pelatihan $pelatihan)
    {
        $validated = $request->validate([
            'judul_sesi' => ['required', 'string', 'max:255'],
            'tanggal' => ['required', 'date'],
            'jam_mulai' => ['required'],
            'jam_selesai' => ['required', 'after:jam_mulai'],
            'batas_terlambat_menit' => ['required', 'integer', 'min:0', 'max:180'],
            'lokasi' => ['required', 'string', 'max:255'],
        ]);

        $validated['pelatihan_id'] = $pelatihan->id;

        $sesi = Sesi::create($validated);
        // Inisialisasi token pertama
        $sesi->rotateTokenIfNeeded(true);

        return redirect()->route('tutor.pelatihan.show', $pelatihan)
            ->with('success', "Sesi '{$sesi->judul_sesi}' berhasil ditambahkan.");
    }

    public function edit(Sesi $sesi)
    {
        $sesi->load('pelatihan');
        return view('tutor.sesi.edit', compact('sesi'));
    }

    public function update(Request $request, Sesi $sesi)
    {
        $validated = $request->validate([
            'judul_sesi' => ['required', 'string', 'max:255'],
            'tanggal' => ['required', 'date'],
            'jam_mulai' => ['required'],
            'jam_selesai' => ['required', 'after:jam_mulai'],
            'batas_terlambat_menit' => ['required', 'integer', 'min:0', 'max:180'],
            'lokasi' => ['required', 'string', 'max:255'],
        ]);

        $sesi->update($validated);

        return redirect()->route('tutor.pelatihan.show', $sesi->pelatihan_id)
            ->with('success', "Sesi '{$sesi->judul_sesi}' berhasil diperbarui.");
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
     * FR-11: Halaman QR Fullscreen dengan rotasi tiap 5 menit + grace period 15 detik
     */
    public function qrFullscreen(Sesi $sesi)
    {
        $sesi->load('pelatihan.tutor');
        $sesi->rotateTokenIfNeeded();

        $qrSvg = QrCode::size(340)->generate($sesi->qr_token);
        $remainingSeconds = $sesi->remainingSeconds();

        return view('tutor.sesi.qr', compact('sesi', 'qrSvg', 'remainingSeconds'));
    }

    /**
     * API JSON untuk memeriksa & auto-rotate token Sesi
     */
    public function tokenStatus(Sesi $sesi)
    {
        $rotated = $sesi->rotateTokenIfNeeded();
        $qrSvg = (string) QrCode::size(340)->generate($sesi->qr_token);

        return response()->json([
            'qr_token' => $sesi->qr_token,
            'kode_manual' => $sesi->kode_manual,
            'remaining_seconds' => $sesi->remainingSeconds(),
            'rotated' => $rotated,
            'qr_svg' => $qrSvg,
        ]);
    }

    /**
     * API JSON untuk paksa rotasi token sekarang
     */
    public function rotateToken(Sesi $sesi)
    {
        $sesi->rotateTokenIfNeeded(true);
        $qrSvg = (string) QrCode::size(340)->generate($sesi->qr_token);

        return response()->json([
            'success' => true,
            'qr_token' => $sesi->qr_token,
            'kode_manual' => $sesi->kode_manual,
            'remaining_seconds' => $sesi->remainingSeconds(),
            'qr_svg' => $qrSvg,
        ]);
    }

    /**
     * FR-12: Layar Pemantauan Live Presensi Sesi
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

            if ($status === 'hadir') $countHadir++;
            elseif ($status === 'terlambat') $countTerlambat++;
            elseif ($status === 'izin') $countIzin++;
            elseif ($status === 'sakit') $countSakit++;
            elseif ($status === 'alpa') $countAlpa++;
            else $countBelumAbsen++;

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
        ];
    }
}
