<?php

namespace App\Console\Commands;

use App\Models\Sesi;
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

        $force = (bool) $this->option('force');
        $totalAlpa = Sesi::finalizePastSessions($force);

        $this->info("Selesai. Total {$totalAlpa} status Alpa otomatis dicatat oleh sistem.");

        return Command::SUCCESS;
    }
}
