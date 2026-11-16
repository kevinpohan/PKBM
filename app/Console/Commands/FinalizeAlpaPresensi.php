<?php

namespace App\Console\Commands;

use App\Models\Pendaftaran;
use App\Models\Presensi;
use App\Models\Sesi;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FinalizeAlpaPresensi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'presensi:finalize-alpa {--force : Jalankan segera untuk semua sesi yang sudah lewat jam_selesai}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatisasi pencatatan status Alpa untuk peserta yang tidak presensi setelah sesi berakhir';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $now = now();
        $this->info("Memeriksa sesi yang telah berakhir pada [{$now->toDateTimeString()}]...");

        $sesis = Sesi::with('pelatihan')
            ->where('is_finalized', false)
            ->get();

        $processedCount = 0;
        $totalAlpa = 0;

        foreach ($sesis as $sesi) {
            $sessionEnd = Carbon::parse($sesi->tanggal->format('Y-m-d') . ' ' . substr((string) $sesi->jam_selesai, 0, 5));

            // Cek apakah waktu sekarang sudah melewati jam selesai sesi
            if ($now->greaterThanOrEqualTo($sessionEnd) || $this->option('force')) {
                // Ambil semua peserta terdaftar yang disetujui pada pelatihan ini
                $approvedUserIds = Pendaftaran::where('pelatihan_id', $sesi->pelatihan_id)
                    ->where('status', 'disetujui')
                    ->pluck('user_id');

                // Ambil user_id yang sudah memiliki presensi (hadir, terlambat, izin, sakit)
                $existingUserIds = Presensi::where('sesi_id', $sesi->id)
                    ->whereIn('user_id', $approvedUserIds)
                    ->pluck('user_id')
                    ->toArray();

                $absentUserIds = $approvedUserIds->diff($existingUserIds);

                foreach ($absentUserIds as $userId) {
                    Presensi::create([
                        'sesi_id' => $sesi->id,
                        'user_id' => $userId,
                        'status' => 'alpa',
                        'waktu_server' => $now,
                        'metode' => 'sistem',
                        'dicatat_oleh' => null,
                    ]);
                    $totalAlpa++;
                }

                $sesi->update(['is_finalized' => true]);
                $processedCount++;
                $this->line("- Sesi ID [{$sesi->id}] '{$sesi->judul_sesi}': dicatat {$absentUserIds->count()} Alpa.");
            }
        }

        $this->info("Selesai. Diproses {$processedCount} sesi dengan total {$totalAlpa} status Alpa baru dicatat.");

        return Command::SUCCESS;
    }
}
