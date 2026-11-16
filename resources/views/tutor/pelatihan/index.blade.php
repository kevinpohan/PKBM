<x-app-layout title="Manajemen Pelatihan">
    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-200">
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Manajemen Pelatihan & Program</h1>
                <p class="text-xs text-slate-500">Kelola kelas Kejar Paket dan Pelatihan Keterampilan Vokasional PKBM.</p>
            </div>
            
            <a href="{{ route('tutor.pelatihan.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                Tambah Pelatihan
            </a>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <form method="GET" action="{{ route('tutor.pelatihan.index') }}" class="flex flex-wrap items-center gap-2">
                <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari nama pelatihan..."
                       class="px-3 py-1.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-1 focus:ring-blue-600">

                <select name="status" class="px-3 py-1.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-1 focus:ring-blue-600">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ ($status ?? '') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="arsip" {{ ($status ?? '') === 'arsip' ? 'selected' : '' }}>Arsip</option>
                </select>

                <button type="submit" class="px-3 py-1.5 bg-slate-800 text-white text-xs font-semibold rounded-xl hover:bg-slate-700 transition">
                    Filter
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="text-[11px] text-slate-400 uppercase bg-slate-50/70 border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4">Nama Pelatihan</th>
                            <th class="py-3 px-4">Tutor Pengampu</th>
                            <th class="py-3 px-4">Total Sesi</th>
                            <th class="py-3 px-4">Peserta Aktif</th>
                            <th class="py-3 px-4">Persetujuan</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pelatihans as $p)
                            <tr class="hover:bg-slate-50/60">
                                <td class="py-3.5 px-4">
                                    <a href="{{ route('tutor.pelatihan.show', $p) }}" class="font-bold text-slate-900 hover:text-blue-600 transition block">
                                        {{ $p->nama }}
                                    </a>
                                    @if($p->deskripsi)
                                        <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ $p->deskripsi }}</p>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-slate-700">
                                    {{ $p->tutor->nama ?? '-' }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="font-bold text-slate-800">{{ $p->sesis_count }}</span> Sesi
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="font-bold text-emerald-700">{{ $p->peserta_disetujui_count }}</span>
                                    <span class="text-slate-400">/ {{ $p->kuota ?? '∞' }}</span>
                                    @if($p->pendaftaran_menunggu_count > 0)
                                        <a href="{{ route('tutor.pelatihan.pendaftaran', $p) }}" class="ml-1 inline-block px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                            +{{ $p->pendaftaran_menunggu_count }} pending
                                        </a>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="text-[11px] font-medium text-slate-600">
                                        {{ $p->butuh_persetujuan ? 'Konfirmasi Manual' : 'Otomatis' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase {{ $p->status === 'aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700' }}">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap space-x-1">
                                    <a href="{{ route('tutor.pelatihan.show', $p) }}" class="px-2.5 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 font-semibold text-[11px] rounded-lg transition">
                                        Sesi
                                    </a>
                                    <a href="{{ route('tutor.pelatihan.pendaftaran', $p) }}" class="px-2.5 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 font-semibold text-[11px] rounded-lg transition">
                                        Peserta
                                    </a>
                                    <a href="{{ route('tutor.pelatihan.edit', $p) }}" class="px-2.5 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 font-semibold text-[11px] rounded-lg transition">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">Belum ada pelatihan terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $pelatihans->links() }}
            </div>
        </div>

    </div>
</x-app-layout>
