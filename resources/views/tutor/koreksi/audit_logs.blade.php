<x-app-layout title="Log Audit Sistem">
    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-200">
            <div>
                <a href="{{ route('tutor.koreksi.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition mb-1">
                    &larr; Kembali ke Koreksi Presensi
                </a>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Log Audit & Rekam Jejak Sistem</h1>
                <p class="text-xs text-slate-500">Seluruh riwayat perubahan status kehadiran tercatat permanen untuk akuntabilitas dan audit resmi.</p>
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ route('tutor.audit-logs') }}" class="flex items-center gap-2">
                <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari nama, alasan..."
                       class="px-3 py-1.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 w-52">
                <button type="submit" class="px-3 py-1.5 bg-slate-800 text-white text-xs font-semibold rounded-xl hover:bg-slate-700 transition">
                    Cari
                </button>
            </form>
        </div>

        <!-- Audit Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="text-[11px] text-slate-400 uppercase bg-slate-50/70 border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4">Waktu Audit</th>
                            <th class="py-3 px-4">Pelaku (Tutor)</th>
                            <th class="py-3 px-4">Peserta Terkait</th>
                            <th class="py-3 px-4">Sesi & Pelatihan</th>
                            <th class="py-3 px-4">Perubahan Status</th>
                            <th class="py-3 px-4">Catatan Alasan Wajib</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50/60">
                                <td class="py-3.5 px-4 font-mono text-[11px] text-slate-500 whitespace-nowrap">
                                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-slate-900 whitespace-nowrap">
                                    {{ $log->pelaku->nama ?? 'Sistem' }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900">{{ $log->presensi->user->nama ?? '-' }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono">{{ $log->presensi->user->nomor_induk ?? '-' }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-semibold text-slate-800">{{ $log->presensi->sesi->judul_sesi ?? '-' }}</div>
                                    <div class="text-[10px] text-slate-500">{{ $log->presensi->sesi->pelatihan->nama ?? '-' }}</div>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        <span class="px-1.5 py-0.5 rounded-sm bg-slate-200 text-slate-700 font-bold text-[10px] uppercase">
                                            {{ $log->status_sebelum ?? 'BELUM' }}
                                        </span>
                                        <span class="text-slate-400 font-bold">&rarr;</span>
                                        <span class="px-1.5 py-0.5 rounded-sm bg-emerald-100 text-emerald-800 font-bold text-[10px] uppercase">
                                            {{ $log->status_sesudah }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-slate-700 leading-relaxed max-w-xs">
                                    "{{ $log->alasan }}"
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">Belum ada riwayat audit log.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $logs->links() }}
            </div>
        </div>

    </div>
</x-app-layout>
