<x-app-layout title="Cari & Daftar Pelatihan">
    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-200">
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Cari & Daftar Pelatihan Baru</h1>
                <p class="text-xs text-slate-500">Temukan program kejar paket atau keterampilan vokasi yang tersedia di PKBM.</p>
            </div>
            
            <!-- Search Form -->
            <form method="GET" action="{{ route('peserta.cari-pelatihan') }}" class="relative w-full sm:w-72">
                <input type="text" name="q" value="{{ $search ?? '' }}"
                       placeholder="Cari nama pelatihan..."
                       class="w-full pl-9 pr-4 py-2 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-hidden transition shadow-xs">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
            </form>
        </div>

        @if($pelatihans->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                <p class="text-sm font-semibold text-slate-700">Tidak ada pelatihan yang ditemukan</p>
                <p class="text-xs text-slate-400 mt-1">Coba kata kunci pencarian yang lain.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($pelatihans as $pelatihan)
                    @php
                        $userStatus = $userEnrollments[$pelatihan->id] ?? null;
                        $sisaKuota = $pelatihan->sisaKuota();
                        $isPenuh = $pelatihan->isPenuh();
                    @endphp
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs flex flex-col justify-between overflow-hidden hover:shadow-md transition">
                        <div class="p-5">
                            
                            <!-- Badges -->
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="text-[11px] font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 px-2 py-0.5 rounded-md">
                                    {{ $pelatihan->tutor->nama ?? 'Tutor PKBM' }}
                                </span>
                                
                                @if($pelatihan->kuota)
                                    <span class="text-[11px] font-semibold {{ $isPenuh ? 'text-rose-700 bg-rose-50 border-rose-200' : 'text-slate-600 bg-slate-100 border-slate-200' }} border px-2 py-0.5 rounded-md">
                                        Sisa: {{ $sisaKuota }} / {{ $pelatihan->kuota }}
                                    </span>
                                @else
                                    <span class="text-[11px] font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md">
                                        Kuota Terbuka
                                    </span>
                                @endif
                            </div>

                            <h3 class="text-base font-bold text-slate-900 leading-snug mb-2">{{ $pelatihan->nama }}</h3>
                            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed mb-4">
                                {{ $pelatihan->deskripsi ?: 'Program pelatihan resmi Pusat Kegiatan Belajar Masyarakat.' }}
                            </p>

                            <div class="text-[11px] text-slate-500 space-y-1 pt-3 border-t border-slate-100">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span>Persetujuan: <strong>{{ $pelatihan->butuh_persetujuan ? 'Perlu Konfirmasi Tutor' : 'Langsung Aktif' }}</strong></span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                    <span>Peserta Aktif: <strong>{{ $pelatihan->peserta_disetujui_count }} warga belajar</strong></span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Action Footer -->
                        <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                            @if($userStatus === 'disetujui')
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 bg-emerald-100/80 px-3 py-1.5 rounded-xl border border-emerald-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    Sudah Terdaftar
                                </span>
                                <a href="{{ route('peserta.pelatihan') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                                    Buka Sesi &rarr;
                                </a>
                            @elseif($userStatus === 'menunggu')
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-amber-700 bg-amber-100 px-3 py-1.5 rounded-xl border border-amber-200">
                                    Menunggu Persetujuan
                                </span>
                            @elseif($userStatus === 'ditolak')
                                <span class="text-xs font-semibold text-rose-600 bg-rose-50 px-3 py-1.5 rounded-xl border border-rose-200">
                                    Pendaftaran Ditolak
                                </span>
                            @elseif($isPenuh)
                                <span class="text-xs font-medium text-slate-500 bg-slate-200 px-3 py-1.5 rounded-xl">
                                    Kuota Penuh
                                </span>
                            @else
                                <form method="POST" action="{{ route('peserta.pelatihan.daftar', $pelatihan) }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full py-2 px-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition cursor-pointer">
                                        {{ $pelatihan->butuh_persetujuan ? 'Ajukan Pendaftaran' : 'Daftar Sekarang' }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-4">
                {{ $pelatihans->links() }}
            </div>
        @endif

    </div>
</x-app-layout>
