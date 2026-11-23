<?php

namespace App\Http\Controllers\Tutor;

use App\Exports\RekapPresensiExport;
use App\Http\Controllers\Controller;
use App\Models\Pelatihan;
use App\Models\Pendaftaran;
use App\Models\Presensi;
use App\Models\Sesi;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $pelatihans = Pelatihan::orderBy('nama')->get();
        $pesertas = User::where('role', 'peserta')->orderBy('nama')->get();

        $pelatihanId = $request->filled('pelatihan_id') ? (int) $request->query('pelatihan_id') : null;
        $userId = $request->filled('user_id') ? (int) $request->query('user_id') : null;
        $startDate = $request->filled('start_date') ? (string) $request->query('start_date') : null;
        $endDate = $request->filled('end_date') ? (string) $request->query('end_date') : null;

        $reportData = $this->generateReportData($pelatihanId, $userId, $startDate, $endDate);

        return view('tutor.laporan.index', array_merge([
            'pelatihans' => $pelatihans,
            'pesertas' => $pesertas,
            'pelatihanId' => $pelatihanId,
            'userId' => $userId,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ], $reportData));
    }

    public function exportExcel(Request $request)
    {
        $pelatihanId = $request->filled('pelatihan_id') ? (int) $request->query('pelatihan_id') : null;
        $userId = $request->filled('user_id') ? (int) $request->query('user_id') : null;
        $startDate = $request->filled('start_date') ? (string) $request->query('start_date') : null;
        $endDate = $request->filled('end_date') ? (string) $request->query('end_date') : null;

        $reportData = $this->generateReportData($pelatihanId, $userId, $startDate, $endDate);

        $filename = 'Laporan_Presensi_PKBM_'.now()->format('Ymd_His').'.xlsx';

        return Excel::download(
            new RekapPresensiExport($reportData['rekapPesertaRaw'], $reportData['detailSesiRaw']),
            $filename
        );
    }

    public function exportPdf(Request $request)
    {
        $pelatihanId = $request->filled('pelatihan_id') ? (int) $request->query('pelatihan_id') : null;
        $userId = $request->filled('user_id') ? (int) $request->query('user_id') : null;
        $startDate = $request->filled('start_date') ? (string) $request->query('start_date') : null;
        $endDate = $request->filled('end_date') ? (string) $request->query('end_date') : null;

        $reportData = $this->generateReportData($pelatihanId, $userId, $startDate, $endDate);

        $selectedPelatihan = $pelatihanId ? Pelatihan::find($pelatihanId) : null;
        $selectedPeserta = $userId ? User::find($userId) : null;

        $pdf = Pdf::loadView('tutor.laporan.pdf', array_merge($reportData, [
            'selectedPelatihan' => $selectedPelatihan,
            'selectedPeserta' => $selectedPeserta,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generatedAt' => now()->translatedFormat('l, d F Y H:i:s'),
        ]))->setPaper('a4', 'landscape');

        $filename = 'Laporan_Presensi_PKBM_'.now()->format('Ymd_His').'.pdf';

        return $pdf->download($filename);
    }

    private function generateReportData(?int $pelatihanId, ?int $userId, ?string $startDate, ?string $endDate): array
    {
        // 1. Ambil pendaftaran yang relevan
        $pendaftaransQuery = Pendaftaran::with(['user', 'pelatihan.sesis' => function ($sq) use ($startDate, $endDate) {
            if ($startDate) {
                $sq->whereDate('tanggal', '>=', $startDate);
            }
            if ($endDate) {
                $sq->whereDate('tanggal', '<=', $endDate);
            }
            $sq->orderBy('tanggal', 'asc')->orderBy('jam_mulai', 'asc');
        }])
            ->where('status', 'disetujui')
            ->when($pelatihanId, fn ($q) => $q->where('pelatihan_id', $pelatihanId))
            ->when($userId, fn ($q) => $q->where('user_id', $userId));

        $pendaftarans = $pendaftaransQuery->get();

        // 2. Siapkan Sheet 1: Rekapitulasi per Peserta
        $rekapPeserta = [];
        $rekapPesertaRaw = [];
        $no = 1;

        foreach ($pendaftarans as $pendaftaran) {
            $user = $pendaftaran->user;
            $pelatihan = $pendaftaran->pelatihan;
            if (! $user || ! $pelatihan) {
                continue;
            }

            $sesis = $pelatihan->sesis;
            $sesiIds = $sesis->pluck('id');

            $totalSesi = $sesis->count();

            // Presensi untuk user dan sesi-sesi ini
            $userPresensis = Presensi::where('user_id', $user->id)
                ->whereIn('sesi_id', $sesiIds)
                ->get();

            $hadir = $userPresensis->where('status', 'hadir')->count();
            $terlambat = $userPresensis->where('status', 'terlambat')->count();
            $izin = $userPresensis->where('status', 'izin')->count();
            $sakit = $userPresensis->where('status', 'sakit')->count();
            $alpa = $userPresensis->where('status', 'alpa')->count();

            $totalMasuk = $hadir + $terlambat;
            $persentase = $totalSesi > 0 ? round(($totalMasuk / $totalSesi) * 100, 1) : 0;

            $item = [
                'no' => $no++,
                'nomor_induk' => $user->nomor_induk ?? '-',
                'nama' => $user->nama,
                'pelatihan' => $pelatihan->nama,
                'total_sesi' => $totalSesi,
                'hadir' => $hadir,
                'terlambat' => $terlambat,
                'izin' => $izin,
                'sakit' => $sakit,
                'alpa' => $alpa,
                'persentase' => $persentase,
            ];

            $rekapPeserta[] = $item;
            $rekapPesertaRaw[] = array_values($item);
        }

        // 3. Siapkan Sheet 2: Log Detail Kehadiran Sesi
        $presensisQuery = Presensi::with(['sesi.pelatihan', 'user', 'auditLogs.pelaku'])
            ->when($pelatihanId, function ($q) use ($pelatihanId) {
                $q->whereHas('sesi', fn ($sq) => $sq->where('pelatihan_id', $pelatihanId));
            })
            ->when($userId, fn ($q) => $q->where('user_id', $userId))
            ->when($startDate, function ($q) use ($startDate) {
                $q->whereHas('sesi', fn ($sq) => $sq->whereDate('tanggal', '>=', $startDate));
            })
            ->when($endDate, function ($q) use ($endDate) {
                $q->whereHas('sesi', fn ($sq) => $sq->whereDate('tanggal', '<=', $endDate));
            })
            ->latest('waktu_server');

        $detailPresensis = $presensisQuery->get();

        $detailSesi = [];
        $detailSesiRaw = [];

        foreach ($detailPresensis as $p) {
            $lastAudit = $p->auditLogs->first();
            $catatanKoreksi = $lastAudit
                ? 'Koreksi oleh '.($lastAudit->pelaku?->nama ?? 'Tutor')." ({$lastAudit->created_at->format('d/m/Y H:i')}): {$lastAudit->alasan}"
                : ($p->catatan ?: '-');

            $jamSesi = '-';
            if ($p->sesi) {
                $jamMulai = substr((string) $p->sesi->jam_mulai, 0, 5);
                $jamSelesai = substr((string) $p->sesi->jam_selesai, 0, 5);
                $jamSesi = "{$jamMulai} - {$jamSelesai}";
            }

            $item = [
                'tanggal' => $p->sesi?->tanggal ? $p->sesi->tanggal->format('d/m/Y') : '-',
                'judul_sesi' => $p->sesi?->judul_sesi ?? '-',
                'pelatihan' => $p->sesi?->pelatihan?->nama ?? '-',
                'jam_sesi' => $jamSesi,
                'lokasi' => $p->sesi?->lokasi ?? '-',
                'nomor_induk' => $p->user?->nomor_induk ?? '-',
                'nama' => $p->user?->nama ?? '-',
                'status' => strtoupper($p->status ?? 'ALPA'),
                'waktu_server' => $p->waktu_server ? $p->waktu_server->format('d/m/Y H:i:s') : '-',
                'metode' => strtoupper($p->metode ?? 'SISTEM'),
                'catatan_koreksi' => $catatanKoreksi,
            ];

            $detailSesi[] = $item;
            $detailSesiRaw[] = array_values($item);
        }

        return [
            'rekapPeserta' => $rekapPeserta,
            'rekapPesertaRaw' => $rekapPesertaRaw,
            'detailSesi' => $detailSesi,
            'detailSesiRaw' => $detailSesiRaw,
            'totalRecords' => count($detailSesi),
        ];
    }
}
