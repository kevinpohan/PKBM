<?php

namespace Tests\Feature;

use App\Models\Pelatihan;
use App\Models\Pendaftaran;
use App\Models\Presensi;
use App\Models\Sesi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class PresensiPkbmTest extends TestCase
{
    use RefreshDatabase;

    protected User $tutor;

    protected User $peserta;

    protected Pelatihan $pelatihan;

    protected Sesi $sesi;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Tutor
        $this->tutor = User::create([
            'nama' => 'Budi Santoso, S.Pd.',
            'username' => 'tutor1',
            'password' => Hash::make('password123'),
            'role' => 'tutor',
            'nomor_induk' => '198501152010011002',
            'no_hp' => '081234567890',
            'is_active' => true,
        ]);

        // Setup Peserta
        $this->peserta = User::create([
            'nama' => 'Ahmad Fauzi',
            'username' => 'ahmad',
            'password' => Hash::make('password123'),
            'role' => 'peserta',
            'nomor_induk' => 'WB-2026-001',
            'no_hp' => '08210001001',
            'is_active' => true,
        ]);

        // Setup Pelatihan
        $this->pelatihan = Pelatihan::create([
            'tutor_id' => $this->tutor->id,
            'nama' => 'Paket C — Kelas IPA Angkatan 2026',
            'deskripsi' => 'Program pendidikan kesetaraan tingkat SMA.',
            'kuota' => 30,
            'butuh_persetujuan' => false,
            'status' => 'aktif',
        ]);

        // Enroll peserta
        Pendaftaran::create([
            'user_id' => $this->peserta->id,
            'pelatihan_id' => $this->pelatihan->id,
            'status' => 'disetujui',
            'tanggal_daftar' => now(),
        ]);

        // Sesi hari ini yang aktif sekarang (window: 15 menit sebelum mulai sampai selesai)
        $today = now()->toDateString();
        $startTime = now()->subMinutes(10)->format('H:i');
        $endTime = now()->addHours(2)->format('H:i');

        $this->sesi = Sesi::create([
            'pelatihan_id' => $this->pelatihan->id,
            'judul_sesi' => 'Praktikum Kimia Organik',
            'tanggal' => $today,
            'jam_mulai' => $startTime,
            'jam_selesai' => $endTime,
            'batas_terlambat_menit' => 15,
            'lokasi' => 'Lab IPA PKBM',
            'qr_token' => Str::random(40),
            'kode_manual' => 'KIM101',
            'token_expired_at' => now()->addMinutes(5),
            'prev_qr_token' => null,
            'prev_token_expired_at' => null,
            'is_finalized' => false,
        ]);
    }

    public function test_login_peserta_and_redirect_to_dashboard(): void
    {
        $response = $this->post('/login', [
            'username' => 'ahmad',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('peserta.dashboard'));
        $this->assertAuthenticatedAs($this->peserta);
    }

    public function test_login_tutor_and_redirect_to_dashboard(): void
    {
        $response = $this->post('/login', [
            'username' => 'tutor1',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('tutor.dashboard'));
        $this->assertAuthenticatedAs($this->tutor);
    }

    public function test_login_invalid_credentials_fails(): void
    {
        $response = $this->post('/login', [
            'username' => 'ahmad',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_inactive_account_cannot_login(): void
    {
        $this->peserta->update(['is_active' => false]);

        $response = $this->post('/login', [
            'username' => 'ahmad',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_peserta_can_browse_and_enroll_pelatihan(): void
    {
        $pelatihan2 = Pelatihan::create([
            'tutor_id' => $this->tutor->id,
            'nama' => 'Pelatihan Desain Grafis',
            'deskripsi' => 'Belajar desain canva & inkscape.',
            'kuota' => 10,
            'butuh_persetujuan' => true,
            'status' => 'aktif',
        ]);

        $this->actingAs($this->peserta);

        $response = $this->post(route('peserta.pelatihan.daftar', $pelatihan2));
        $response->assertRedirect();

        $this->assertDatabaseHas('pendaftarans', [
            'user_id' => $this->peserta->id,
            'pelatihan_id' => $pelatihan2->id,
            'status' => 'menunggu', // butuh_persetujuan = true
        ]);
    }

    public function test_peserta_qr_submission_hadir_success(): void
    {
        $this->actingAs($this->peserta);

        $response = $this->post(route('peserta.presensi.submit'), [
            'sesi_id' => $this->sesi->id,
            'token' => $this->sesi->qr_token,
        ]);

        $response->assertRedirect(route('peserta.presensi.result'));
        $response->assertSessionHas('result');
        $result = session('result');
        $this->assertTrue($result['success']);
        $this->assertEquals('hadir', $result['status']);

        $this->assertDatabaseHas('presensis', [
            'sesi_id' => $this->sesi->id,
            'user_id' => $this->peserta->id,
            'status' => 'hadir',
            'metode' => 'qr',
        ]);
    }

    public function test_peserta_presensi_terlambat_when_past_tolerance(): void
    {
        // Sesuaikan jam mulai sesi menjadi 30 menit yang lalu (batas terlambat 15 menit)
        $this->sesi->update([
            'jam_mulai' => now()->subMinutes(30)->format('H:i'),
            'batas_terlambat_menit' => 15,
        ]);

        $this->actingAs($this->peserta);

        $response = $this->post(route('peserta.presensi.submit'), [
            'sesi_id' => $this->sesi->id,
            'token' => $this->sesi->qr_token,
        ]);

        $response->assertRedirect(route('peserta.presensi.result'));
        $result = session('result');
        $this->assertTrue($result['success']);
        $this->assertEquals('terlambat', $result['status']);

        $this->assertDatabaseHas('presensis', [
            'sesi_id' => $this->sesi->id,
            'user_id' => $this->peserta->id,
            'status' => 'terlambat',
        ]);
    }

    public function test_duplicate_presensi_is_rejected(): void
    {
        // Presensi pertama
        Presensi::create([
            'sesi_id' => $this->sesi->id,
            'user_id' => $this->peserta->id,
            'status' => 'hadir',
            'waktu_server' => now(),
            'metode' => 'qr',
        ]);

        $this->actingAs($this->peserta);

        // Percobaan presensi kedua
        $response = $this->post(route('peserta.presensi.submit'), [
            'sesi_id' => $this->sesi->id,
            'token' => $this->sesi->qr_token,
        ]);

        $response->assertRedirect(route('peserta.presensi.result'));
        $result = session('result');
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Sudah presensi', $result['alasan']);
    }

    public function test_presensi_with_manual_6_digit_code(): void
    {
        $this->actingAs($this->peserta);

        $response = $this->post(route('peserta.presensi.submit'), [
            'sesi_id' => $this->sesi->id,
            'kode_manual' => 'KIM101',
        ]);

        $response->assertRedirect(route('peserta.presensi.result'));
        $result = session('result');
        $this->assertTrue($result['success']);

        $this->assertDatabaseHas('presensis', [
            'sesi_id' => $this->sesi->id,
            'user_id' => $this->peserta->id,
            'metode' => 'kode_manual',
        ]);
    }

    public function test_grace_period_15_seconds_presensi_accepted(): void
    {
        // Simulasikan pergantian token: token lama masuk prev_qr_token dengan toleransi 15 detik
        $oldToken = $this->sesi->qr_token;
        $this->sesi->rotateTokenIfNeeded(true);

        $this->assertEquals($oldToken, $this->sesi->prev_qr_token);
        $this->assertTrue(now()->lessThanOrEqualTo($this->sesi->prev_token_expired_at));

        $this->actingAs($this->peserta);

        // Peserta mengirim token lama dalam masa tenggang jeda 15 detik
        $response = $this->post(route('peserta.presensi.submit'), [
            'sesi_id' => $this->sesi->id,
            'token' => $oldToken,
        ]);

        $response->assertRedirect(route('peserta.presensi.result'));
        $result = session('result');
        $this->assertTrue($result['success']);
    }

    public function test_expired_qr_token_rejected(): void
    {
        $this->sesi->update([
            'token_expired_at' => now()->subMinute(),
            'prev_token_expired_at' => now()->subMinute(),
        ]);

        $this->actingAs($this->peserta);

        $response = $this->post(route('peserta.presensi.submit'), [
            'sesi_id' => $this->sesi->id,
            'token' => $this->sesi->qr_token,
        ]);

        $response->assertRedirect(route('peserta.presensi.result'));
        $result = session('result');
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('QR kedaluwarsa', $result['alasan']);
    }

    public function test_tutor_can_rotate_token(): void
    {
        $this->actingAs($this->tutor);

        $oldToken = $this->sesi->qr_token;

        $response = $this->postJson(route('tutor.sesi.rotate-token', $this->sesi));
        $response->assertOk();
        $response->assertJsonStructure(['success', 'qr_token', 'kode_manual', 'remaining_seconds', 'qr_svg']);

        $this->sesi->refresh();
        $this->assertNotEquals($oldToken, $this->sesi->qr_token);
        $this->assertEquals($oldToken, $this->sesi->prev_qr_token);
    }

    public function test_tutor_can_view_live_data(): void
    {
        $this->actingAs($this->tutor);

        $response = $this->getJson(route('tutor.sesi.live-data', $this->sesi));
        $response->assertOk();
        $response->assertJsonStructure([
            'total_enrolled',
            'count_hadir',
            'count_terlambat',
            'count_izin',
            'count_sakit',
            'count_alpa',
            'count_belum_absen',
            'attendees',
        ]);
    }

    public function test_tutor_koreksi_presensi_with_mandatory_audit_log(): void
    {
        $this->actingAs($this->tutor);

        $response = $this->post(route('tutor.koreksi.store'), [
            'sesi_id' => $this->sesi->id,
            'user_id' => $this->peserta->id,
            'status_sesudah' => 'izin',
            'alasan' => 'Peserta mengirim surat keterangan izin dinas luar kota',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('presensis', [
            'sesi_id' => $this->sesi->id,
            'user_id' => $this->peserta->id,
            'status' => 'izin',
            'metode' => 'koreksi_tutor',
            'dicatat_oleh' => $this->tutor->id,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'pelaku_id' => $this->tutor->id,
            'status_sesudah' => 'izin',
            'alasan' => 'Peserta mengirim surat keterangan izin dinas luar kota',
        ]);
    }

    public function test_command_finalize_alpa_presensi(): void
    {
        // Buat peserta kedua yang tidak hadir
        $peserta2 = User::create([
            'nama' => 'Bambang Pamungkas',
            'username' => 'bambang',
            'password' => Hash::make('password123'),
            'role' => 'peserta',
            'is_active' => true,
        ]);
        Pendaftaran::create([
            'user_id' => $peserta2->id,
            'pelatihan_id' => $this->pelatihan->id,
            'status' => 'disetujui',
            'tanggal_daftar' => now(),
        ]);

        // Buat sesi yang sudah selesai 1 jam yang lalu
        $pastSesi = Sesi::create([
            'pelatihan_id' => $this->pelatihan->id,
            'judul_sesi' => 'Sesi Kemarin Selesai',
            'tanggal' => now()->toDateString(),
            'jam_mulai' => now()->subHours(3)->format('H:i'),
            'jam_selesai' => now()->subHour()->format('H:i'),
            'batas_terlambat_menit' => 15,
            'lokasi' => 'Ruang 1',
            'qr_token' => Str::random(40),
            'kode_manual' => 'SES101',
            'token_expired_at' => now()->subHour(),
            'is_finalized' => false,
        ]);

        // $this->peserta hadir, tapi $peserta2 tidak hadir sama sekali
        Presensi::create([
            'sesi_id' => $pastSesi->id,
            'user_id' => $this->peserta->id,
            'status' => 'hadir',
            'waktu_server' => now()->subHours(3),
            'metode' => 'qr',
        ]);

        // Jalankan perintah artisan background scheduler (FR-10)
        $this->artisan('presensi:finalize-alpa')
            ->assertSuccessful();

        // Verifikasi $peserta2 otomatis tercatat Alpa oleh metode sistem
        $this->assertDatabaseHas('presensis', [
            'sesi_id' => $pastSesi->id,
            'user_id' => $peserta2->id,
            'status' => 'alpa',
            'metode' => 'sistem',
        ]);

        // Verifikasi sesi ditandai finalized
        $pastSesi->refresh();
        $this->assertTrue($pastSesi->is_finalized);
    }

    public function test_tutor_laporan_excel_and_pdf_export(): void
    {
        $this->actingAs($this->tutor);

        // Test HTML index laporan
        $response = $this->get(route('tutor.laporan.index'));
        $response->assertOk();

        // Test Export Excel (.xlsx)
        $excelResponse = $this->get(route('tutor.laporan.excel'));
        $excelResponse->assertOk();
        $this->assertStringContainsString('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', $excelResponse->headers->get('content-type'));

        // Test Export PDF (.pdf)
        $pdfResponse = $this->get(route('tutor.laporan.pdf'));
        $pdfResponse->assertOk();
        $this->assertEquals('application/pdf', $pdfResponse->headers->get('content-type'));
    }

    public function test_tutor_bisa_membuka_form_dan_membuat_sesi_baru(): void
    {
        $this->actingAs($this->tutor);

        // 1. Akses form tambah sesi global
        $responseGlobal = $this->get(route('tutor.sesi.create'));
        $responseGlobal->assertOk();
        $responseGlobal->assertSee('Tambah Sesi Pembelajaran Baru');

        // 2. Akses form tambah sesi terikat pelatihan
        $responseScoped = $this->get(route('tutor.pelatihan.sesi.create', $this->pelatihan));
        $responseScoped->assertOk();
        $responseScoped->assertSee($this->pelatihan->nama);

        // 3. Simpan sesi baru
        $storeResponse = $this->post(route('tutor.sesi.store'), [
            'pelatihan_id' => $this->pelatihan->id,
            'judul_sesi' => 'Sesi Algoritma Dasar',
            'tanggal' => now()->toDateString(),
            'jam_mulai' => now()->subMinutes(5)->format('H:i'),
            'jam_selesai' => now()->addHours(2)->format('H:i'),
            'batas_terlambat_menit' => 15,
            'lokasi' => 'Lab Komputer 1',
        ]);

        $storeResponse->assertRedirect(route('tutor.pelatihan.show', $this->pelatihan->id));
        $this->assertDatabaseHas('sesis', [
            'pelatihan_id' => $this->pelatihan->id,
            'judul_sesi' => 'Sesi Algoritma Dasar',
        ]);
    }

    public function test_tutor_bisa_aktifkan_sesi_langsung_ke_waktu_sekarang(): void
    {
        $this->actingAs($this->tutor);

        // Buat sesi di waktu lampau
        $sesiLampau = Sesi::create([
            'pelatihan_id' => $this->pelatihan->id,
            'judul_sesi' => 'Sesi Pagi',
            'tanggal' => now()->toDateString(),
            'jam_mulai' => '07:00:00',
            'jam_selesai' => '08:30:00',
            'batas_terlambat_menit' => 15,
            'lokasi' => 'Ruang 1',
            'qr_token' => Str::random(40),
            'kode_manual' => 'PAGI01',
            'token_expired_at' => now()->subHours(2),
            'is_finalized' => false,
        ]);

        $this->assertFalse($sesiLampau->isWithinAttendanceWindow());

        // Tutor klik Buka Sekarang (activate-now)
        $response = $this->post(route('tutor.sesi.activate-now', $sesiLampau));
        $response->assertRedirect();

        $sesiLampau->refresh();
        $this->assertTrue($sesiLampau->isWithinAttendanceWindow());
    }

    public function test_peserta_bisa_absen_via_url_scan_langsung(): void
    {
        $this->actingAs($this->peserta);

        // Simulasi scan dari kamera HP biasa yang membuka URL langsung
        $scanUrl = route('presensi.scan-url', [
            'sesi' => $this->sesi->id,
            'token' => $this->sesi->qr_token,
        ]);

        $response = $this->get($scanUrl);
        $response->assertRedirect(route('peserta.presensi.result'));

        $this->assertDatabaseHas('presensis', [
            'sesi_id' => $this->sesi->id,
            'user_id' => $this->peserta->id,
            'status' => 'hadir',
            'metode' => 'qr',
        ]);
    }

    public function test_peserta_bisa_submit_presensi_dengan_url_penuh(): void
    {
        $this->actingAs($this->peserta);

        // Buat sesi baru
        $sesiBaru = Sesi::create([
            'pelatihan_id' => $this->pelatihan->id,
            'judul_sesi' => 'Sesi Percobaan Full URL',
            'tanggal' => now()->toDateString(),
            'jam_mulai' => now()->subMinutes(5)->format('H:i'),
            'jam_selesai' => now()->addHours(2)->format('H:i'),
            'batas_terlambat_menit' => 15,
            'lokasi' => 'Ruang 2',
            'qr_token' => 'VALIDTOKEN123456789012345678901234567890',
            'kode_manual' => 'FL1234',
            'token_expired_at' => now()->addMinutes(5),
            'is_finalized' => false,
        ]);

        // Input hasil scan adalah string URL utuh
        $fullScannedUrl = 'http://localhost:8000/presensi/scan?sesi='.$sesiBaru->id.'&token='.$sesiBaru->qr_token;

        $response = $this->post(route('peserta.presensi.submit'), [
            'sesi_id' => $sesiBaru->id,
            'token' => $fullScannedUrl,
        ]);

        $response->assertRedirect(route('peserta.presensi.result'));

        $this->assertDatabaseHas('presensis', [
            'sesi_id' => $sesiBaru->id,
            'user_id' => $this->peserta->id,
            'status' => 'hadir',
            'metode' => 'qr',
        ]);
    }

    public function test_peserta_terdaftar_tidak_absen_sesi_berakhir_otomatis_alpa(): void
    {
        $pastSesi = Sesi::create([
            'pelatihan_id' => $this->pelatihan->id,
            'judul_sesi' => 'Sesi Lampau Wajib Hadir',
            'tanggal' => now()->toDateString(),
            'jam_mulai' => now()->subHours(4)->format('H:i'),
            'jam_selesai' => now()->subHours(2)->format('H:i'),
            'batas_terlambat_menit' => 15,
            'lokasi' => 'Ruang 1',
            'qr_token' => Str::random(40),
            'kode_manual' => 'SES999',
            'token_expired_at' => now()->subHours(2),
            'is_finalized' => false,
        ]);

        $this->actingAs($this->peserta);
        $response = $this->get(route('peserta.pelatihan'));
        $response->assertOk();

        $response->assertSee('Alpa');
        $response->assertDontSee('Selesai');

        $this->assertDatabaseHas('presensis', [
            'sesi_id' => $pastSesi->id,
            'user_id' => $this->peserta->id,
            'status' => 'alpa',
            'metode' => 'sistem',
        ]);
    }
}
