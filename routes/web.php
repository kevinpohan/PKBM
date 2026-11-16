<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\Tutor\DashboardController as TutorDashboardController;
use App\Http\Controllers\Tutor\KoreksiController;
use App\Http\Controllers\Tutor\LaporanController;
use App\Http\Controllers\Tutor\PelatihanController;
use App\Http\Controllers\Tutor\SesiController;
use App\Http\Controllers\Tutor\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Sistem Presensi Digital PKBM
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->isTutor()
            ? redirect()->route('tutor.dashboard')
            : redirect()->route('peserta.dashboard');
    }
    return redirect()->route('login');
});

// Autentikasi (FR-01)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Profil Pengguna & Ganti Password (FR-02)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile.index');
    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Peserta (Warga Belajar) Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:peserta'])->prefix('peserta')->as('peserta.')->group(function () {
    Route::get('/dashboard', [PesertaController::class, 'dashboard'])->name('dashboard');
    Route::get('/pelatihan', [PesertaController::class, 'pelatihanList'])->name('pelatihan');
    Route::get('/cari-pelatihan', [PesertaController::class, 'cariPelatihan'])->name('cari-pelatihan');
    Route::post('/pelatihan/{pelatihan}/daftar', [PesertaController::class, 'daftarPelatihan'])->name('pelatihan.daftar');

    // Presensi Sesi (FR-06, FR-07, FR-08, FR-09)
    Route::get('/presensi/pindai/{sesi}', [PesertaController::class, 'showPindai'])->name('presensi.pindai');
    Route::post('/presensi/submit', [PesertaController::class, 'submitPresensi'])->name('presensi.submit');
    Route::get('/presensi/result', [PesertaController::class, 'presensiResult'])->name('presensi.result');

    // Riwayat (FR-14)
    Route::get('/riwayat', [PesertaController::class, 'riwayat'])->name('riwayat');
});

/*
|--------------------------------------------------------------------------
| Tutor / Instruktur Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:tutor'])->prefix('tutor')->as('tutor.')->group(function () {
    Route::get('/dashboard', [TutorDashboardController::class, 'index'])->name('dashboard');

    // Manajemen Pelatihan & Approval Pendaftaran (FR-05, FR-16)
    Route::resource('pelatihan', PelatihanController::class);
    Route::get('/pelatihan/{pelatihan}/pendaftaran', [PelatihanController::class, 'pendaftarans'])->name('pelatihan.pendaftaran');
    Route::post('/pelatihan/pendaftaran/{pendaftaran}/approve', [PelatihanController::class, 'approvePendaftaran'])->name('pelatihan.pendaftaran.approve');
    Route::post('/pelatihan/pendaftaran/{pendaftaran}/reject', [PelatihanController::class, 'rejectPendaftaran'])->name('pelatihan.pendaftaran.reject');

    // Sesi Pembelajaran & Core QR (FR-08, FR-09, FR-11, FR-12)
    Route::get('/pelatihan/{pelatihan}/sesi/create', [SesiController::class, 'create'])->name('pelatihan.sesi.create');
    Route::post('/pelatihan/{pelatihan}/sesi', [SesiController::class, 'store'])->name('pelatihan.sesi.store');
    Route::get('/sesi/{sesi}/edit', [SesiController::class, 'edit'])->name('sesi.edit');
    Route::put('/sesi/{sesi}', [SesiController::class, 'update'])->name('sesi.update');
    Route::delete('/sesi/{sesi}', [SesiController::class, 'destroy'])->name('sesi.destroy');

    // Layar QR Fullscreen & Polling/Rotate API (FR-11)
    Route::get('/sesi/{sesi}/qr', [SesiController::class, 'qrFullscreen'])->name('sesi.qr');
    Route::get('/sesi/{sesi}/token-status', [SesiController::class, 'tokenStatus'])->name('sesi.token-status');
    Route::post('/sesi/{sesi}/rotate-token', [SesiController::class, 'rotateToken'])->name('sesi.rotate-token');

    // Pemantauan Live Presensi (FR-12)
    Route::get('/sesi/{sesi}/live', [SesiController::class, 'live'])->name('sesi.live');
    Route::get('/sesi/{sesi}/live-data', [SesiController::class, 'liveData'])->name('sesi.live-data');

    // Manajemen Pengguna & Impor CSV (FR-02, FR-16, Sec 9.1)
    Route::get('/users/template', [UserController::class, 'downloadTemplate'])->name('users.template');
    Route::post('/users/import', [UserController::class, 'importCsv'])->name('users.import');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::resource('users', UserController::class);

    // Koreksi Presensi & Log Audit (FR-13)
    Route::get('/koreksi', [KoreksiController::class, 'index'])->name('koreksi.index');
    Route::post('/koreksi', [KoreksiController::class, 'storeKoreksi'])->name('koreksi.store');
    Route::get('/audit-logs', [KoreksiController::class, 'auditLogs'])->name('audit-logs');

    // Rekap & Ekspor Laporan Excel / PDF (FR-15, Sec 9.2)
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
    Route::get('/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
});
