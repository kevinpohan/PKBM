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
use Illuminate\Support\Facades\DB;

class KoreksiController extends Controller
{
    public function index(Request $request)
    {
        $pelatihanId = $request->query('pelatihan_id');
        $sesiId = $request->query('sesi_id');
        $status = $request->query('status');
        $search = $request->query('q');

        $pelatihans = Pelatihan::with('sesis')->where('status', 'aktif')->orderBy('nama')->get();
        $selectedPelatihan = $pelatihanId ? Pelatihan::find($pelatihanId) : null;
        $sesis = $selectedPelatihan ? $selectedPelatihan->sesis : collect();

        $presensisQuery = Presensi::with(['sesi.pelatihan', 'user', 'dicatatOleh'])
            ->when($pelatihanId, function ($q) use ($pelatihanId) {
                $q->whereHas('sesi', fn ($sq) => $sq->where('pelatihan_id', $pelatihanId));
            })
            ->when($sesiId, fn ($q) => $q->where('sesi_id', $sesiId))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($uq) use ($search) {
                    $uq->where('nama', 'like', "%{$search}%")
                       ->orWhere('nomor_induk', 'like', "%{$search}%");
                });
            })
            ->latest('waktu_server');

        $presensis = $presensisQuery->paginate(15);

        return view('tutor.koreksi.index', compact(
            'pelatihans',
            'sesis',
            'selectedPelatihan',
            'pelatihanId',
            'sesiId',
            'status',
            'search',
            'presensis'
        ));
    }

    /**
     * Simpan koreksi status presensi dengan kewajiban mengisi alasan
     */
    public function storeKoreksi(Request $request)
    {
        $validated = $request->validate([
            'sesi_id' => ['required', 'exists:sesis,id'],
            'user_id' => ['required', 'exists:users,id'],
            'status_sesudah' => ['required', 'in:hadir,terlambat,izin,sakit,alpa'],
            'alasan' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'alasan.required' => 'Catatan alasan koreksi wajib diisi.',
            'alasan.min' => 'Alasan koreksi minimal 5 karakter agar terdokumentasi jelas di log audit.',
        ]);

        $tutorId = Auth::id();

        DB::transaction(function () use ($validated, $tutorId) {
            $presensi = Presensi::where('sesi_id', $validated['sesi_id'])
                ->where('user_id', $validated['user_id'])
                ->first();

            $statusSebelum = $presensi ? $presensi->status : null;

            if ($presensi) {
                $presensi->update([
                    'status' => $validated['status_sesudah'],
                    'metode' => 'koreksi_tutor',
                    'dicatat_oleh' => $tutorId,
                ]);
            } else {
                $presensi = Presensi::create([
                    'sesi_id' => $validated['sesi_id'],
                    'user_id' => $validated['user_id'],
                    'status' => $validated['status_sesudah'],
                    'waktu_server' => now(),
                    'metode' => 'koreksi_tutor',
                    'dicatat_oleh' => $tutorId,
                ]);
            }

            // Simpan jejak di audit_logs
            AuditLog::create([
                'pelaku_id' => $tutorId,
                'presensi_id' => $presensi->id,
                'aksi' => $statusSebelum ? 'koreksi_status' : 'input_manual_tutor',
                'status_sebelum' => $statusSebelum,
                'status_sesudah' => $validated['status_sesudah'],
                'alasan' => $validated['alasan'],
                'created_at' => now(),
            ]);
        });

        return back()->with('success', 'Status presensi berhasil dikoreksi dan tercatat permanen pada Log Audit.');
    }

    /**
     * Lihat riwayat log audit perubahan presensi
     */
    public function auditLogs(Request $request)
    {
        $search = $request->query('q');

        $logs = AuditLog::with(['pelaku', 'presensi.user', 'presensi.sesi.pelatihan'])
            ->when($search, function ($query, $search) {
                $query->whereHas('presensi.user', function ($uq) use ($search) {
                    $uq->where('nama', 'like', "%{$search}%");
                })->orWhereHas('pelaku', function ($pq) use ($search) {
                    $pq->where('nama', 'like', "%{$search}%");
                })->orWhere('alasan', 'like', "%{$search}%");
            })
            ->latest('created_at')
            ->paginate(20);

        return view('tutor.koreksi.audit_logs', compact('logs', 'search'));
    }
}
