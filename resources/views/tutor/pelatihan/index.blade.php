<x-app-layout title="Manajemen Pelatihan">
    <div class="flex flex-col gap-6">

        <!-- Top Header Bar -->
        <div class="bg-surface-container-lowest p-5 sm:p-6 rounded-2xl shadow-xs border border-outline-variant/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-primary"></span>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-on-surface tracking-tight">Manajemen Pelatihan & Program</h1>
                </div>
                <p class="text-xs text-on-surface-variant mt-1">Kelola kelas Kejar Paket dan Pelatihan Keterampilan Vokasional PKBM Karawang.</p>
            </div>
            
            <a href="{{ route('tutor.pelatihan.create') }}"
               class="px-4 py-2.5 bg-primary hover:bg-primary-container text-on-primary font-bold text-xs rounded-xl shadow-xs transition self-start sm:self-auto flex items-center gap-1.5 cursor-pointer">
                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                <span>Tambah Pelatihan Baru</span>
            </a>
        </div>

        <!-- Filter Bar -->
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 p-4 sm:p-5 shadow-xs">
            <form method="GET" action="{{ route('tutor.pelatihan.index') }}" class="flex flex-wrap items-center gap-3">
                <div class="relative flex-1 min-w-[200px]">
                    <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari nama program pelatihan..."
                           class="w-full pl-9 pr-3 py-2 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary focus:outline-none">
                    <span class="material-symbols-outlined absolute left-2.5 top-2 text-[18px] text-outline pointer-events-none">search</span>
                </div>

                <select name="status" class="px-3 py-2 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface font-semibold focus:ring-2 focus:ring-primary focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ ($status ?? '') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="arsip" {{ ($status ?? '') === 'arsip' ? 'selected' : '' }}>Arsip</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-container text-on-primary font-bold text-xs rounded-xl shadow-xs transition">
                    Filter
                </button>
            </form>
        </div>

        <!-- Table View -->
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-on-surface">
                    <thead class="text-[11px] text-on-surface-variant uppercase bg-surface-container-low border-b border-outline-variant/20">
                        <tr>
                            <th class="py-3 px-4">Nama Pelatihan</th>
                            <th class="py-3 px-4">Tutor Pengampu</th>
                            <th class="py-3 px-4">Total Sesi</th>
                            <th class="py-3 px-4">Peserta Aktif</th>
                            <th class="py-3 px-4">Sistem Masuk</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/15">
                        @forelse($pelatihans as $p)
                            <tr class="hover:bg-surface-container-low/50 transition">
                                <td class="py-3.5 px-4">
                                    <a href="{{ route('tutor.pelatihan.show', $p) }}" class="font-bold text-on-surface hover:text-primary transition block text-sm">
                                        {{ $p->nama }}
                                    </a>
                                    @if($p->deskripsi)
                                        <p class="text-[11px] text-on-surface-variant line-clamp-1 mt-0.5">{{ $p->deskripsi }}</p>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-on-surface font-medium">
                                    {{ $p->tutor->nama ?? 'Instruktur' }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="font-bold text-on-surface">{{ $p->sesis_count }}</span> Sesi
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="font-bold text-secondary">{{ $p->peserta_disetujui_count }}</span>
                                    <span class="text-on-surface-variant">/ {{ $p->kuota ?? '∞' }}</span>
                                    @if($p->pendaftaran_menunggu_count > 0)
                                        <a href="{{ route('tutor.pelatihan.pendaftaran', $p) }}" class="ml-1 inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-900">
                                            +{{ $p->pendaftaran_menunggu_count }} pending
                                        </a>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="text-[11px] font-medium text-on-surface-variant">
                                        {{ $p->butuh_persetujuan ? 'Persetujuan Tutor' : 'Langsung Aktif' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $p->status === 'aktif' ? 'bg-secondary-container text-on-secondary-container' : 'bg-surface-container text-on-surface-variant' }}">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap space-x-1">
                                    <a href="{{ route('tutor.pelatihan.show', $p) }}" class="px-2.5 py-1.5 bg-primary-fixed text-on-primary-fixed hover:bg-primary-fixed-dim font-bold text-[11px] rounded-lg transition inline-flex items-center gap-1">
                                        <span>Kelola Sesi</span>
                                    </a>
                                    <a href="{{ route('tutor.pelatihan.pendaftaran', $p) }}" class="px-2.5 py-1.5 bg-surface-container hover:bg-surface-container-high text-on-surface font-bold text-[11px] rounded-lg border border-outline-variant/30 transition">
                                        Peserta
                                    </a>
                                    <a href="{{ route('tutor.pelatihan.edit', $p) }}" class="px-2.5 py-1.5 bg-surface-container hover:bg-surface-container-high text-on-surface font-bold text-[11px] rounded-lg border border-outline-variant/30 transition">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-on-surface-variant italic">Belum ada program pelatihan yang terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
