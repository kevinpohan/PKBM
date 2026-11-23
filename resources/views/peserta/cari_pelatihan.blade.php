<x-app-layout title="Cari & Daftar Pelatihan">
    <div class="flex flex-col gap-6">

        <!-- Header Bar with Search Input -->
        <div class="bg-surface-container-lowest p-5 sm:p-6 rounded-2xl shadow-xs border border-outline-variant/30 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-primary"></span>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-on-surface tracking-tight">Katalog Pelatihan Terbuka</h1>
                </div>
                <p class="text-xs text-on-surface-variant mt-1">Jelajahi program kejar paket atau pelatihan vokasi keterampilan yang dapat Anda ikuti.</p>
            </div>
            
            <!-- Search Form -->
            <form method="GET" action="{{ route('peserta.cari-pelatihan') }}" class="relative w-full md:w-80">
                <input type="text" name="q" value="{{ $search ?? '' }}"
                       placeholder="Cari nama pelatihan / keahlian..."
                       class="w-full pl-10 pr-4 py-2.5 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary focus:outline-none transition">
                <span class="material-symbols-outlined absolute left-3 top-2.5 text-[20px] text-outline pointer-events-none">search</span>
            </form>
        </div>

        @if($pelatihans->isEmpty())
            <div class="bg-surface-container-lowest rounded-2xl border border-dashed border-outline-variant/40 p-12 text-center">
                <div class="w-12 h-12 rounded-2xl bg-surface-container text-on-surface-variant flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-outlined text-[28px]">search_off</span>
                </div>
                <p class="text-sm font-bold text-on-surface">Tidak ada pelatihan yang cocok</p>
                <p class="text-xs text-on-surface-variant mt-1">Coba gunakan kata kunci pencarian yang lain atau periksa kembali nanti.</p>
            </div>
        @else
            <!-- Grid responsive: 1 col on mobile, 2 col on tablet, 3 col on desktop -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($pelatihans as $pelatihan)
                    @php
                        $userStatus = $userEnrollments[$pelatihan->id] ?? null;
                        $sisaKuota = $pelatihan->sisaKuota();
                        $isPenuh = $pelatihan->isPenuh();
                    @endphp
                    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-xs flex flex-col justify-between overflow-hidden hover:shadow-md transition">
                        <div class="p-5 sm:p-6">
                            
                            <!-- Badges Header -->
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="text-xs font-bold text-primary bg-primary-fixed px-2.5 py-0.5 rounded-full truncate max-w-[160px]">
                                    Tutor: {{ $pelatihan->tutor->nama ?? 'Instruktur PKBM' }}
                                </span>
                                
                                @if($pelatihan->kuota)
                                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-full {{ $isPenuh ? 'text-error bg-error-container' : 'text-on-surface-variant bg-surface-container' }}">
                                        Sisa: {{ $sisaKuota }}/{{ $pelatihan->kuota }}
                                    </span>
                                @else
                                    <span class="text-[11px] font-bold text-secondary bg-secondary-container px-2 py-0.5 rounded-full">
                                        Kuota Terbuka
                                    </span>
                                @endif
                            </div>

                            <h3 class="text-base font-bold text-on-surface leading-snug mb-2">{{ $pelatihan->nama }}</h3>
                            <p class="text-xs text-on-surface-variant line-clamp-3 leading-relaxed mb-4">
                                {{ $pelatihan->deskripsi ?: 'Program pelatihan resmi Pusat Kegiatan Belajar Masyarakat (PKBM) Karawang.' }}
                            </p>

                            <!-- Meta Info -->
                            <div class="text-[11px] text-on-surface-variant space-y-1.5 pt-3 border-t border-outline-variant/20">
                                <div class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px] text-primary">verified</span>
                                    <span>Pendaftaran: <strong>{{ $pelatihan->butuh_persetujuan ? 'Perlu Persetujuan Tutor' : 'Langsung Aktif' }}</strong></span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px] text-secondary">groups</span>
                                    <span>Peserta Terdaftar: <strong>{{ $pelatihan->peserta_disetujui_count }} Warga Belajar</strong></span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Action Footer -->
                        <div class="p-4 bg-surface-container-low/70 border-t border-outline-variant/20 flex items-center justify-between">
                            @if($userStatus === 'disetujui')
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-secondary bg-secondary-container px-3 py-1.5 rounded-xl">
                                    <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                    <span>Sudah Terdaftar</span>
                                </span>
                                <a href="{{ route('peserta.pelatihan') }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-0.5">
                                    <span>Buka Sesi</span>
                                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                                </a>
                            @elseif($userStatus === 'menunggu')
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-800 bg-amber-100 px-3 py-1.5 rounded-xl">
                                    <span class="material-symbols-outlined text-[16px]">hourglass_top</span>
                                    <span>Menunggu Persetujuan</span>
                                </span>
                            @elseif($userStatus === 'ditolak')
                                <span class="text-xs font-bold text-error bg-error-container px-3 py-1.5 rounded-xl">
                                    Pendaftaran Ditolak
                                </span>
                            @elseif($isPenuh)
                                <span class="text-xs font-bold text-on-surface-variant bg-surface-container px-3 py-1.5 rounded-xl">
                                    Kuota Penuh
                                </span>
                            @else
                                <form method="POST" action="{{ route('peserta.pelatihan.daftar', $pelatihan) }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full py-2.5 px-3 bg-primary hover:bg-primary-container text-on-primary font-bold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
                                        <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
                                        <span>{{ $pelatihan->butuh_persetujuan ? 'Ajukan Pendaftaran' : 'Daftar Sekarang' }}</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</x-app-layout>
