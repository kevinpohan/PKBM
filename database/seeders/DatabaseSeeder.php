<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\Pelatihan;
use App\Models\Pendaftaran;
use App\Models\Presensi;
use App\Models\Sesi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Tutors
        $tutor1 = User::create([
            'nama' => 'Budi Santoso, S.Pd.',
            'username' => 'tutor1',
            'password' => Hash::make('password123'),
            'role' => 'tutor',
            'no_hp' => '081234567890',
            'nomor_induk' => '198501152010011002',
            'is_active' => true,
        ]);

        $tutor2 = User::create([
            'nama' => 'Siti Rahayu, M.Pd.',
            'username' => 'tutor2',
            'password' => Hash::make('password123'),
            'role' => 'tutor',
            'no_hp' => '081234567891',
            'nomor_induk' => '199003202015022001',
            'is_active' => true,
        ]);

        // 2. Create Peserta (Warga Belajar)
        $pesertaData = [
            ['nama' => 'Ahmad Fauzi', 'username' => 'ahmad', 'nomor_induk' => 'WB-2026-001', 'no_hp' => '08210001001'],
            ['nama' => 'Dewi Lestari', 'username' => 'dewi', 'nomor_induk' => 'WB-2026-002', 'no_hp' => '08210001002'],
            ['nama' => 'Rizki Pratama', 'username' => 'rizki', 'nomor_induk' => 'WB-2026-003', 'no_hp' => '08210001003'],
            ['nama' => 'Siti Nurhaliza', 'username' => 'siti', 'nomor_induk' => 'WB-2026-004', 'no_hp' => '08210001004'],
            ['nama' => 'Bambang Pamungkas', 'username' => 'bambang', 'nomor_induk' => 'WB-2026-005', 'no_hp' => '08210001005'],
            ['nama' => 'Mega Utami', 'username' => 'mega', 'nomor_induk' => 'WB-2026-006', 'no_hp' => '08210001006'],
            ['nama' => 'Indra Wijaya', 'username' => 'indra', 'nomor_induk' => 'WB-2026-007', 'no_hp' => '08210001007'],
            ['nama' => 'Putri Ayu', 'username' => 'putri', 'nomor_induk' => 'WB-2026-008', 'no_hp' => '08210001008'],
        ];

        $pesertas = [];
        foreach ($pesertaData as $p) {
            $pesertas[] = User::create([
                'nama' => $p['nama'],
                'username' => $p['username'],
                'password' => Hash::make('password123'),
                'role' => 'peserta',
                'no_hp' => $p['no_hp'],
                'nomor_induk' => $p['nomor_induk'],
                'is_active' => true,
            ]);
        }

        // 3. Create Pelatihan
        $pelatihan1 = Pelatihan::create([
            'tutor_id' => $tutor1->id,
            'nama' => 'Paket C — Kelas IPA Angkatan 2026',
            'deskripsi' => 'Program pendidikan kesetaraan tingkat SMA/MA peminatan Ilmu Pengetahuan Alam untuk warga belajar PKBM.',
            'kuota' => 30,
            'butuh_persetujuan' => false,
            'status' => 'aktif',
        ]);

        $pelatihan2 = Pelatihan::create([
            'tutor_id' => $tutor1->id,
            'nama' => 'Keterampilan Digital & Desain Grafis',
            'deskripsi' => 'Pelatihan keterampilan vokasional pembuatan konten media sosial, editing grafis, dan branding digital UMKM.',
            'kuota' => 20,
            'butuh_persetujuan' => true,
            'status' => 'aktif',
        ]);

        $pelatihan3 = Pelatihan::create([
            'tutor_id' => $tutor2->id,
            'nama' => 'Kewirausahaan & Tata Boga PKBM',
            'deskripsi' => 'Pelatihan pengolahan makanan higienis, perhitungan HPP, dan pemasaran produk kuliner mandiri.',
            'kuota' => 15,
            'butuh_persetujuan' => false,
            'status' => 'aktif',
        ]);

        // 4. Enroll Pesertas
        // All enrolled in Pelatihan 1 (Paket C)
        foreach ($pesertas as $p) {
            Pendaftaran::create([
                'user_id' => $p->id,
                'pelatihan_id' => $pelatihan1->id,
                'status' => 'disetujui',
                'tanggal_daftar' => now()->subDays(30),
            ]);
        }

        // 4 participants enrolled in Pelatihan 2 (Design) - 3 approved, 1 waiting
        for ($i = 0; $i < 3; $i++) {
            Pendaftaran::create([
                'user_id' => $pesertas[$i]->id,
                'pelatihan_id' => $pelatihan2->id,
                'status' => 'disetujui',
                'tanggal_daftar' => now()->subDays(10),
            ]);
        }
        Pendaftaran::create([
            'user_id' => $pesertas[3]->id,
            'pelatihan_id' => $pelatihan2->id,
            'status' => 'menunggu',
            'tanggal_daftar' => now()->subDays(2),
        ]);

        // 2 participants in Pelatihan 3
        for ($i = 4; $i < 6; $i++) {
            Pendaftaran::create([
                'user_id' => $pesertas[$i]->id,
                'pelatihan_id' => $pelatihan3->id,
                'status' => 'disetujui',
                'tanggal_daftar' => now()->subDays(5),
            ]);
        }

        // 5. Create Sesi
        // Sesi 1: Past session 1 (7 days ago)
        $pastDate = now()->subDays(7)->toDateString();
        $sesi1 = Sesi::create([
            'pelatihan_id' => $pelatihan1->id,
            'judul_sesi' => 'Pengantar Biologi Sel & Jaringan',
            'tanggal' => $pastDate,
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'batas_terlambat_menit' => 15,
            'lokasi' => 'Ruang Teori 1 - PKBM',
            'qr_token' => Str::random(40),
            'kode_manual' => 'BIO001',
            'token_expired_at' => Carbon::parse($pastDate . ' 10:00:00'),
            'is_finalized' => true,
        ]);

        // Attendance for Sesi 1
        Presensi::create([
            'sesi_id' => $sesi1->id,
            'user_id' => $pesertas[0]->id,
            'status' => 'hadir',
            'waktu_server' => Carbon::parse($pastDate . ' 07:55:00'),
            'metode' => 'qr',
        ]);
        Presensi::create([
            'sesi_id' => $sesi1->id,
            'user_id' => $pesertas[1]->id,
            'status' => 'terlambat',
            'waktu_server' => Carbon::parse($pastDate . ' 08:22:00'),
            'metode' => 'kode_manual',
        ]);
        $presensiIzin = Presensi::create([
            'sesi_id' => $sesi1->id,
            'user_id' => $pesertas[2]->id,
            'status' => 'izin',
            'waktu_server' => Carbon::parse($pastDate . ' 08:30:00'),
            'metode' => 'koreksi_tutor',
            'dicatat_oleh' => $tutor1->id,
        ]);
        AuditLog::create([
            'pelaku_id' => $tutor1->id,
            'presensi_id' => $presensiIzin->id,
            'aksi' => 'koreksi_status',
            'status_sebelum' => 'alpa',
            'status_sesudah' => 'izin',
            'alasan' => 'Peserta mengirimkan surat izin kerja shift pagi',
            'created_at' => Carbon::parse($pastDate . ' 11:00:00'),
        ]);

        for ($i = 3; $i < 6; $i++) {
            Presensi::create([
                'sesi_id' => $sesi1->id,
                'user_id' => $pesertas[$i]->id,
                'status' => 'hadir',
                'waktu_server' => Carbon::parse($pastDate . ' 08:05:00'),
                'metode' => 'qr',
            ]);
        }
        // Last two were absent (Alpa)
        for ($i = 6; $i < 8; $i++) {
            Presensi::create([
                'sesi_id' => $sesi1->id,
                'user_id' => $pesertas[$i]->id,
                'status' => 'alpa',
                'waktu_server' => Carbon::parse($pastDate . ' 10:01:00'),
                'metode' => 'sistem',
            ]);
        }

        // Sesi 2: Sesi Hari Ini (Active / Live Now)
        // Set hours so it spans around current time to allow immediate live testing
        $today = now()->toDateString();
        $startTime = now()->subMinutes(20)->format('H:i');
        $endTime = now()->addHours(2)->format('H:i');

        $sesiHariIni = Sesi::create([
            'pelatihan_id' => $pelatihan1->id,
            'judul_sesi' => 'Praktikum Kimia: Larutan Asam & Basa',
            'tanggal' => $today,
            'jam_mulai' => $startTime,
            'jam_selesai' => $endTime,
            'batas_terlambat_menit' => 15,
            'lokasi' => 'Laboratorium IPA PKBM',
            'qr_token' => Str::random(40),
            'kode_manual' => 'KIM101',
            'token_expired_at' => now()->addMinutes(5),
            'prev_qr_token' => Str::random(40),
            'prev_token_expired_at' => now()->addSeconds(15),
            'is_finalized' => false,
        ]);

        // A couple participants already attended today's session
        Presensi::create([
            'sesi_id' => $sesiHariIni->id,
            'user_id' => $pesertas[0]->id,
            'status' => 'hadir',
            'waktu_server' => now()->subMinutes(15),
            'metode' => 'qr',
        ]);
        Presensi::create([
            'sesi_id' => $sesiHariIni->id,
            'user_id' => $pesertas[1]->id,
            'status' => 'terlambat',
            'waktu_server' => now()->subMinutes(2),
            'metode' => 'kode_manual',
        ]);

        // Sesi 3: Upcoming session (in 3 days)
        $futureDate = now()->addDays(3)->toDateString();
        Sesi::create([
            'pelatihan_id' => $pelatihan1->id,
            'judul_sesi' => 'Fisika Terapan: Mekanika & Dinamika Gerak',
            'tanggal' => $futureDate,
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:30',
            'batas_terlambat_menit' => 15,
            'lokasi' => 'Ruang Teori 1 - PKBM',
            'qr_token' => Str::random(40),
            'kode_manual' => 'FIS202',
            'token_expired_at' => Carbon::parse($futureDate . ' 11:30:00'),
            'is_finalized' => false,
        ]);

        // Sesi 4: Pelatihan 2 (Design) session
        Sesi::create([
            'pelatihan_id' => $pelatihan2->id,
            'judul_sesi' => 'Dasar Tipografi dan Color Theory Digital',
            'tanggal' => now()->addDays(1)->toDateString(),
            'jam_mulai' => '13:00',
            'jam_selesai' => '15:30',
            'batas_terlambat_menit' => 20,
            'lokasi' => 'Lab Komputer PKBM Lt. 2',
            'qr_token' => Str::random(40),
            'kode_manual' => 'DES101',
            'token_expired_at' => now()->addDays(1),
            'is_finalized' => false,
        ]);
    }
}
