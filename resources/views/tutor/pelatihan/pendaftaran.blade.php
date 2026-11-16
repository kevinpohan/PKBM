<x-app-layout title="Pendaftaran Peserta: {{ $pelatihan->nama }}">
    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-200">
            <div>
                <a href="{{ route('tutor.pelatihan.show', $pelatihan) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition mb-1">
                    &larr; Kembali ke Detail Pelatihan
                </a>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Manajemen Peserta & Persetujuan</h1>
                <p class="text-xs text-slate-500">{{ $pelatihan->nama }} &bull; Sisa Kuota: <strong>{{ $pelatihan->sisaKuota() ?? 'Tanpa Batas' }}</strong></p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Daftar Warga Belajar Terdaftar</span>
                <span class="text-xs text-slate-500">Total {{ $pendaftarans->count() }} orang</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="text-[11px] text-slate-400 uppercase bg-slate-50/50 border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4">Nama Peserta</th>
                            <th class="py-3 px-4">Username</th>
                            <th class="py-3 px-4">No. Induk</th>
                            <th class="py-3 px-4">No. HP</th>
                            <th class="py-3 px-4">Tanggal Daftar</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Persetujuan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pendaftarans as $p)
                            <tr class="hover:bg-slate-50/60">
                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    {{ $p->user->nama }}
                                </td>
                                <td class="py-3.5 px-4 font-mono text-[11px] text-slate-500">
                                    {{ $p->user->username }}
                                </td>
                                <td class="py-3.5 px-4 text-slate-600">
                                    {{ $p->user->nomor_induk ?? '-' }}
                                </td>
                                <td class="py-3.5 px-4 text-slate-600">
                                    {{ $p->user->no_hp ?? '-' }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-slate-500">
                                    {{ $p->tanggal_daftar->format('d/m/Y H:i') }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if($p->status === 'disetujui')
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            DISETUJUI
                                        </span>
                                    @elseif($p->status === 'menunggu')
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                            MENUNGGU
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                            DITOLAK
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap space-x-1.5">
                                    @if($p->status !== 'disetujui')
                                        <form method="POST" action="{{ route('tutor.pelatihan.pendaftaran.approve', $p) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-[11px] rounded-lg transition cursor-pointer">
                                                Setujui
                                            </button>
                                        </form>
                                    @endif

                                    @if($p->status !== 'ditolak')
                                        <form method="POST" action="{{ route('tutor.pelatihan.pendaftaran.reject', $p) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-semibold text-[11px] rounded-lg transition cursor-pointer">
                                                Tolak
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">Belum ada peserta yang mendaftar di pelatihan ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
