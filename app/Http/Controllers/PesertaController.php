<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Pendaftaran;
use App\Models\Presensi;
use App\Models\Sesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class PesertaController extends Controller
{
    public function dashboard()
    {
        // Finalisasi sesi yang telah lewat jam pelajaran agar tercatat otomatis Alpa
        Sesi::finalizePastSessions();

        $user = Auth::user();

        // Total Pelatihan Diikuti (disetujui)
        $totalPelatihan = $user->pendaftarans()->where('status', 'disetujui')->count();

        // Total Kehadiran (hadir + terlambat)
        $totalHadir = $user->presensis()->whereIn('status', ['hadir', 'terlambat'])->count();
        $totalIzinSakit = $user->presensis()->whereIn('status', ['izin', 'sakit'])->count();
        $totalAlpa = $user->presensis()->where('status', 'alpa')->count();

        // Ambil pelatihan yang diikuti (disetujui) beserta sesi hari ini
        $pelatihanIds = $user->pendaftarans()->where('status', 'disetujui')->pluck('pelatihan_id');

        $today = now()->toDateString();
        $sesiHariIni = Sesi::with(['pelatihan', 'presensis' => function ($q) use ($user) {
            $q->where('user_id', $user->id);
        }])
            ->whereIn('pelatihan_id', $pelatihanIds)
            ->whereDate('tanggal', $today)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        return view('peserta.dashboard', compact(
            'user',
            'totalPelatihan',
            'totalHadir',
            'totalIzinSakit',
            'totalAlpa',
            'sesiHariIni'
        ));
    }

    public function pelatihanList()
    {
        // Finalisasi sesi yang telah lewat jam pelajaran agar tercatat otomatis Alpa
        Sesi::finalizePastSessions();

        $user = Auth::user();

        $pendaftarans = Pendaftaran::with(['pelatihan.tutor', 'pelatihan.sesis' => function ($q) {
            $q->orderBy('tanggal', 'asc')->orderBy('jam_mulai', 'asc');
        }, 'pelatihan.sesis.presensis' => function ($q) use ($user) {
            $q->where('user_id', $user->id);
        }])
            ->where('user_id', $user->id)
            ->latest('tanggal_daftar')
            ->get();

        return view('peserta.pelatihan', compact('pendaftarans'));
    }

    public function cariPelatihan(Request $request)
    {
        $user = Auth::user();
        $search = $request->query('q');

        $pelatihans = Pelatihan::with('tutor')
            ->where('status', 'aktif')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            })
            ->withCount(['pesertas as peserta_disetujui_count' => function ($q) {
                $q->where('pendaftarans.status', 'disetujui');
            }])
            ->latest()
            ->paginate(9);

        // Ambil data status pendaftaran user untuk setiap pelatihan
        $userEnrollments = Pendaftaran::where('user_id', $user->id)
            ->pluck('status', 'pelatihan_id')
            ->toArray();

        return view('peserta.cari_pelatihan', compact('pelatihans', 'search', 'userEnrollments'));
    }

    public function daftarPelatihan(Request $request, Pelatihan $pelatihan)
    {
        $user = Auth::user();

        // Cek apakah sudah terdaftar
        $existing = Pendaftaran::where('user_id', $user->id)
            ->where('pelatihan_id', $pelatihan->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah mendaftar pada pelatihan ini.');
        }

        // Cek kuota
        if ($pelatihan->isPenuh()) {
            return back()->with('error', 'Pendaftaran gagal: kuota pelatihan sudah penuh.');
        }

        // Tentukan status pendaftaran
        $status = $pelatihan->butuh_persetujuan ? 'menunggu' : 'disetujui';

        Pendaftaran::create([
            'user_id' => $user->id,
            'pelatihan_id' => $pelatihan->id,
            'status' => $status,
            'tanggal_daftar' => now(),
        ]);

        $message = $status === 'disetujui'
            ? 'Pendaftaran berhasil! Anda telah langsung aktif di pelatihan ini.'
            : 'Pendaftaran berhasil dikirim! Menunggu konfirmasi persetujuan dari Tutor.';

        return back()->with('success', $message);
    }

    public function showPindai(Sesi $sesi)
    {
        $user = Auth::user();

        // Cek apakah terdaftar dan disetujui
        $isEnrolled = Pendaftaran::where('user_id', $user->id)
            ->where('pelatihan_id', $sesi->pelatihan_id)
            ->where('status', 'disetujui')
            ->exists();

        if (! $isEnrolled) {
            return redirect()->route('peserta.pelatihan')
                ->with('error', 'Anda bukan peserta aktif yang disetujui pada pelatihan ini.');
        }

        // Cek apakah sudah presensi
        $presensi = Presensi::where('sesi_id', $sesi->id)
            ->where('user_id', $user->id)
            ->first();

        $now = now();
        $isBeforeWindow = $sesi->isBeforeWindow($now);
        $isAfterWindow = $sesi->isAfterWindow($now);
        $isWithinWindow = $sesi->isWithinAttendanceWindow($now);

        return view('peserta.pindai', compact(
            'sesi',
            'presensi',
            'isBeforeWindow',
            'isAfterWindow',
            'isWithinWindow'
        ));
    }

    /**
     * Endpoint untuk pemindaian langsung via URL dari kamera ponsel biasa / Google Lens
     */
    public function scanFromUrl(Request $request)
    {
        if (! Auth::check()) {
            session()->put('url.intended', $request->fullUrl());

            return redirect()->route('login')->with('info', 'Silakan masuk ke akun Warga Belajar Anda untuk konfirmasi presensi.');
        }

        $user = Auth::user();
        if ($user->isTutor()) {
            return redirect()->route('tutor.dashboard')->with('error', 'Anda masuk sebagai Tutor. Pemindaian presensi khusus untuk akun Warga Belajar (Peserta).');
        }

        $sesiId = $request->query('sesi') ?: $request->query('sesi_id');
        $token = $request->query('token');

        $request->merge([
            'sesi_id' => $sesiId,
            'token' => $token,
        ]);

        return $this->submitPresensi($request);
    }

    public function submitPresensi(Request $request)
    {
        $user = Auth::user();

        // FR-10 / PRD Sec 10: Rate limiting pada input presensi (maksimal 5 percobaan per menit)
        $throttleKey = 'presensi-submit:'.$user->id;
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return redirect()->route('peserta.presensi.result')->with('result', [
                'success' => false,
                'alasan' => "Terlalu banyak percobaan presensi. Harap tunggu {$seconds} detik.",
                'nama_sesi' => 'Presensi Sesi',
                'nama_peserta' => $user->nama,
            ]);
        }

        $rawTokenInput = trim((string) ($request->token ?: $request->kode_manual));

        // Normalisasi jika hasil pemindaian adalah URL utuh
        $tokenInput = $rawTokenInput;
        $sesiId = $request->sesi_id;

        if (str_contains($rawTokenInput, 'token=')) {
            $parsed = parse_url($rawTokenInput);
            if (! empty($parsed['query'])) {
                parse_str($parsed['query'], $query);
                if (! empty($query['token'])) {
                    $tokenInput = trim($query['token']);
                }
                if (empty($sesiId) && ! empty($query['sesi'])) {
                    $sesiId = $query['sesi'];
                }
            }
        }

        if (empty($sesiId)) {
            return redirect()->route('peserta.dashboard')->with('error', 'Sesi pembelajaran tidak ditemukan.');
        }

        $sesi = Sesi::with('pelatihan')->findOrFail($sesiId);
        $metode = $request->filled('token') || str_contains($rawTokenInput, 'token=') ? 'qr' : 'kode_manual';

        if (empty($tokenInput)) {
            RateLimiter::hit($throttleKey, 60);

            return redirect()->route('peserta.presensi.result')->with('result', [
                'success' => false,
                'alasan' => 'Kode QR atau Kode Manual tidak boleh kosong.',
                'nama_sesi' => $sesi->judul_sesi,
                'nama_peserta' => $user->nama,
            ]);
        }

        // 1. Cek kepesertaan
        $isEnrolled = Pendaftaran::where('user_id', $user->id)
            ->where('pelatihan_id', $sesi->pelatihan_id)
            ->where('status', 'disetujui')
            ->exists();

        if (! $isEnrolled) {
            RateLimiter::hit($throttleKey, 60);

            return redirect()->route('peserta.presensi.result')->with('result', [
                'success' => false,
                'alasan' => 'Bukan peserta pelatihan ini. Anda belum terdaftar atau belum disetujui pada pelatihan terkait.',
                'nama_sesi' => $sesi->judul_sesi,
                'nama_peserta' => $user->nama,
            ]);
        }

        // 2. Cek apakah sudah presensi
        $alreadyAttended = Presensi::where('sesi_id', $sesi->id)
            ->where('user_id', $user->id)
            ->first();

        // Jika sudah ada presensi manual/hadir/izin/sakit, tolak.
        // Namun jika status sebelumnya adalah 'alpa' otomatis dari sistem dan sesi saat ini aktif/dibuka lagi oleh tutor, perbolehkan update.
        if ($alreadyAttended && ! ($alreadyAttended->status === 'alpa' && $alreadyAttended->metode === 'sistem')) {
            return redirect()->route('peserta.presensi.result')->with('result', [
                'success' => false,
                'alasan' => 'Sudah presensi sebelumnya dengan status: '.strtoupper($alreadyAttended->status).' pada '.$alreadyAttended->waktu_server->format('d/m/Y H:i:s'),
                'nama_sesi' => $sesi->judul_sesi,
                'nama_peserta' => $user->nama,
            ]);
        }

        // 3. Cek jendela waktu
        $now = now();
        if ($sesi->isBeforeWindow($now)) {
            RateLimiter::hit($throttleKey, 60);

            return redirect()->route('peserta.presensi.result')->with('result', [
                'success' => false,
                'alasan' => "Di luar jam sesi: Presensi belum dibuka. Presensi baru dibuka 15 menit sebelum kelas dimulai (pukul {$sesi->window_buka->format('H:i')}).",
                'nama_sesi' => $sesi->judul_sesi,
                'nama_peserta' => $user->nama,
            ]);
        }

        if ($sesi->isAfterWindow($now)) {
            RateLimiter::hit($throttleKey, 60);

            return redirect()->route('peserta.presensi.result')->with('result', [
                'success' => false,
                'alasan' => "Di luar jam sesi: Sesi telah selesai pada pukul {$sesi->waktu_selesai->format('H:i')}. Presensi sudah ditutup.",
                'nama_sesi' => $sesi->judul_sesi,
                'nama_peserta' => $user->nama,
            ]);
        }

        // 4. Cek token / kode manual (termasuk rotasi 5 menit & toleransi jeda 15 detik)
        if (! $sesi->isTokenValid($tokenInput)) {
            RateLimiter::hit($throttleKey, 60);

            return redirect()->route('peserta.presensi.result')->with('result', [
                'success' => false,
                'alasan' => 'QR kedaluwarsa atau Kode Manual salah. Token diperbarui tiap 5 menit. Harap pindai kode terbaru di layar tutor.',
                'nama_sesi' => $sesi->judul_sesi,
                'nama_peserta' => $user->nama,
            ]);
        }

        // 5. Tentukan status Hadir vs Terlambat
        $status = $sesi->isLate($now) ? 'terlambat' : 'hadir';

        // 6. Simpan atau perbarui Presensi
        if ($alreadyAttended) {
            $alreadyAttended->update([
                'status' => $status,
                'waktu_server' => $now,
                'metode' => $metode,
                'dicatat_oleh' => null,
            ]);
            $presensi = $alreadyAttended;
        } else {
            $presensi = Presensi::create([
                'sesi_id' => $sesi->id,
                'user_id' => $user->id,
                'status' => $status,
                'waktu_server' => $now,
                'metode' => $metode,
                'dicatat_oleh' => null,
            ]);
        }

        RateLimiter::clear($throttleKey);

        return redirect()->route('peserta.presensi.result')->with('result', [
            'success' => true,
            'status' => $status,
            'nama_peserta' => $user->nama,
            'nama_sesi' => $sesi->judul_sesi,
            'nama_pelatihan' => $sesi->pelatihan->nama,
            'lokasi' => $sesi->lokasi,
            'jam_server' => $now->format('H:i:s T'),
            'tanggal' => $now->translatedFormat('l, d F Y'),
            'metode' => $metode === 'qr' ? 'Pemindaian Kamera QR' : 'Input Kode Manual 6-Digit',
        ]);
    }

    public function presensiResult()
    {
        $result = session('result');
        if (! $result) {
            return redirect()->route('peserta.dashboard');
        }

        return view('peserta.status_presensi', compact('result'));
    }

    public function riwayat()
    {
        // Finalisasi sesi lampau agar status Alpa akurat
        Sesi::finalizePastSessions();

        $user = Auth::user();

        $pendaftarans = Pendaftaran::with(['pelatihan.sesis' => function ($q) {
            $q->orderBy('tanggal', 'asc')->orderBy('jam_mulai', 'asc');
        }, 'pelatihan.sesis.presensis' => function ($q) use ($user) {
            $q->where('user_id', $user->id);
        }])
            ->where('user_id', $user->id)
            ->where('status', 'disetujui')
            ->get();

        // Hitung persentase kehadiran per pelatihan
        $rekapPerPelatihan = [];
        foreach ($pendaftarans as $pendaftaran) {
            $pelatihan = $pendaftaran->pelatihan;
            $totalSesi = $pelatihan->sesis->count();

            $hadir = 0;
            $terlambat = 0;
            $izin = 0;
            $sakit = 0;
            $alpa = 0;

            foreach ($pelatihan->sesis as $sesi) {
                $p = $sesi->presensis->first();
                if ($p) {
                    if ($p->status === 'hadir') {
                        $hadir++;
                    } elseif ($p->status === 'terlambat') {
                        $terlambat++;
                    } elseif ($p->status === 'izin') {
                        $izin++;
                    } elseif ($p->status === 'sakit') {
                        $sakit++;
                    } elseif ($p->status === 'alpa') {
                        $alpa++;
                    }
                }
            }

            $totalMasuk = $hadir + $terlambat;
            $persentase = $totalSesi > 0 ? round(($totalMasuk / $totalSesi) * 100, 1) : 0;

            $rekapPerPelatihan[] = [
                'pelatihan' => $pelatihan,
                'totalSesi' => $totalSesi,
                'hadir' => $hadir,
                'terlambat' => $terlambat,
                'izin' => $izin,
                'sakit' => $sakit,
                'alpa' => $alpa,
                'totalMasuk' => $totalMasuk,
                'persentase' => $persentase,
            ];
        }

        return view('peserta.riwayat', compact('rekapPerPelatihan'));
    }
}
