<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pelatihans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->integer('kuota')->nullable();
            $table->boolean('butuh_persetujuan')->default(false);
            $table->enum('status', ['aktif', 'arsip'])->default('aktif');
            $table->timestamps();
        });

        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('pelatihan_id')->constrained('pelatihans')->cascadeOnDelete();
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->dateTime('tanggal_daftar');
            $table->timestamps();

            $table->unique(['user_id', 'pelatihan_id']);
        });

        Schema::create('sesis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelatihan_id')->constrained('pelatihans')->cascadeOnDelete();
            $table->string('judul_sesi');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('batas_terlambat_menit')->default(15);
            $table->string('lokasi');
            $table->string('qr_token', 255)->nullable();
            $table->string('kode_manual', 10)->nullable();
            $table->timestamp('token_expired_at')->nullable();
            $table->string('prev_qr_token', 255)->nullable();
            $table->timestamp('prev_token_expired_at')->nullable();
            $table->boolean('is_finalized')->default(false);
            $table->timestamps();
        });

        Schema::create('presensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesi_id')->constrained('sesis')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['hadir', 'terlambat', 'izin', 'sakit', 'alpa']);
            $table->timestamp('waktu_server');
            $table->enum('metode', ['qr', 'kode_manual', 'koreksi_tutor', 'sistem']);
            $table->foreignId('dicatat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['sesi_id', 'user_id']);
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelaku_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('presensi_id')->constrained('presensis')->cascadeOnDelete();
            $table->string('aksi');
            $table->string('status_sebelum')->nullable();
            $table->string('status_sesudah');
            $table->text('alasan');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('presensis');
        Schema::dropIfExists('sesis');
        Schema::dropIfExists('pendaftarans');
        Schema::dropIfExists('pelatihans');
    }
};
