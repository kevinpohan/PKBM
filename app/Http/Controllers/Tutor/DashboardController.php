<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Pelatihan;
use App\Models\Pendaftaran;
use App\Models\Presensi;
use App\Models\Sesi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Statistik Master
        $totalPeserta = User::where('role', 'peserta')->where('is_active', true)->count();
        $totalTutor = User::where('role', 'tutor')->where('is_active', true)->count();
        $totalPelatihanAktif = Pelatihan::where('status', 'aktif')->count();

        // Sesi Hari Ini
        $today = now()->toDateString();
        $sesiHariIni = Sesi::with(['pelatihan.tutor', 'presensis'])
            ->whereDate('tanggal', $today)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        // Pendaftaran Menunggu Persetujuan
        $pendingPendaftarans = Pendaftaran::with(['user', 'pelatihan'])
            ->where('status', 'menunggu')
            ->latest('tanggal_daftar')
            ->take(5)
            ->get();

        // Log Audit Koreksi Terbaru
        $recentAuditLogs = AuditLog::with(['pelaku', 'presensi.user', 'presensi.sesi.pelatihan'])
            ->latest('created_at')
            ->take(6)
            ->get();

        return view('tutor.dashboard', compact(
            'user',
            'totalPeserta',
            'totalTutor',
            'totalPelatihanAktif',
            'sesiHariIni',
            'pendingPendaftarans',
            'recentAuditLogs'
        ));
    }
}
