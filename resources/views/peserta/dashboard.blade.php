<x-app-layout title="Dashboard Warga Belajar">
    <div class="flex flex-col gap-6">

        <!-- 1. Warm Welcome Hero Profile Card (Stitch Component 1) -->
        <section class="bg-surface-container-lowest rounded-2xl p-5 sm:p-6 shadow-xs border border-outline-variant/30 relative overflow-hidden">
            <div class="absolute -right-8 -bottom-8 w-40 h-40 rounded-full bg-primary-fixed/30 pointer-events-none blur-xl"></div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
                <div class="flex items-center gap-4">
                    <div class="relative shrink-0">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-primary text-on-primary font-extrabold text-xl sm:text-2xl flex items-center justify-center shadow-xs">
                            {{ strtoupper(substr($user->nama, 0, 2)) }}
                        </div>
                        <span class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-secondary rounded-full ring-2 ring-surface-container-lowest" title="Akun Warga Belajar Aktif"></span>
                    </div>

                    <div class="flex flex-col min-w-0">
                        <h1 class="text-xl sm:text-2xl font-extrabold text-on-surface truncate">Selamat datang, {{ $user->nama }}! 👋</h1>
                        <p class="text-xs sm:text-sm text-on-surface-variant line-clamp-1 mt-0.5">Warga Belajar PKBM Terbuka • Program Kesetaraan & Vokasi</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-secondary bg-secondary-container/50 px-2.5 py-0.5 rounded-full">
                                <span class="material-symbols-outlined text-[14px]">school</span>
                                <span>ID: {{ $user->username }}</span>
                            </span>
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-primary bg-primary-fixed px-2.5 py-0.5 rounded-full">
                                <span class="material-symbols-outlined text-[14px]">verified</span>
                                <span>Aktif</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Action Button in Hero (Desktop view) -->
                <div class="hidden sm:flex items-center gap-2 shrink-0">
                    <a href="{{ route('peserta.cari-pelatihan') }}"
                       class="px-3.5 py-2 rounded-xl bg-surface-container hover:bg-surface-container-high text-xs font-bold text-primary border border-outline-variant/30 transition flex items-center gap-1">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                        <span>Cari Pelatihan Baru</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- 2. Quick Stats Overview Cards (Stitch Component 2 - 2 cols on mobile, 4 cols on desktop) -->
        <section aria-label="Ringkasan Statistik Kehadiran" class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
            <!-- Stat 1: Pelatihan Diikuti -->
            <div class="bg-surface-container-lowest rounded-2xl p-4 sm:p-5 shadow-xs border border-outline-variant/30 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="w-10 h-10 rounded-xl bg-primary-fixed text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">local_library</span>
                    </span>
                    <span class="text-[11px] bg-secondary-container text-on-secondary-container px-2 py-0.5 rounded-full font-bold">
                        Aktif
                    </span>
                </div>
                <div class="mt-3">
                    <p class="text-2xl sm:text-3xl font-extrabold text-primary leading-none">{{ $totalPelatihan }}</p>
                    <p class="text-xs sm:text-sm font-bold text-on-surface mt-1.5">Pelatihan Diikuti</p>
                    <p class="text-[11px] text-on-surface-variant mt-0.5">Program terdaftar</p>
                </div>
            </div>

            <!-- Stat 2: Total Kehadiran -->
            <div class="bg-surface-container-lowest rounded-2xl p-4 sm:p-5 shadow-xs border border-outline-variant/30 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="w-10 h-10 rounded-xl bg-secondary-container text-secondary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">verified</span>
                    </span>
                    <span class="text-[11px] text-secondary font-bold flex items-center gap-0.5 bg-secondary-container/40 px-2 py-0.5 rounded-full">
                        <span class="material-symbols-outlined text-[13px]">trending_up</span> Hadir
                    </span>
                </div>
                <div class="mt-3">
                    <p class="text-2xl sm:text-3xl font-extrabold text-secondary leading-none">{{ $totalHadir }}</p>
                    <p class="text-xs sm:text-sm font-bold text-on-surface mt-1.5">Total Kehadiran</p>
                    <p class="text-[11px] text-on-surface-variant mt-0.5">Tepat waktu & terlambat</p>
                </div>
            </div>

            <!-- Stat 3: Izin & Sakit -->
            <div class="bg-surface-container-lowest rounded-2xl p-4 sm:p-5 shadow-xs border border-outline-variant/30 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="w-10 h-10 rounded-xl bg-surface-container-high text-on-surface flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">clinical_notes</span>
                    </span>
                    <span class="text-[11px] text-on-surface-variant bg-surface-container px-2 py-0.5 rounded-full font-semibold">
                        Izin
                    </span>
                </div>
                <div class="mt-3">
                    <p class="text-2xl sm:text-3xl font-extrabold text-on-surface leading-none">{{ $totalIzinSakit }}</p>
                    <p class="text-xs sm:text-sm font-bold text-on-surface mt-1.5">Izin & Sakit</p>
                    <p class="text-[11px] text-on-surface-variant mt-0.5">Tercatat resmi</p>
                </div>
            </div>

            <!-- Stat 4: Alpa / Tidak Hadir -->
            <div class="bg-surface-container-lowest rounded-2xl p-4 sm:p-5 shadow-xs border border-outline-variant/30 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="w-10 h-10 rounded-xl bg-error-container text-error flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">person_off</span>
                    </span>
                    <span class="text-[11px] text-error font-bold bg-error-container/40 px-2 py-0.5 rounded-full">
                        Alpa
                    </span>
                </div>
                <div class="mt-3">
                    <p class="text-2xl sm:text-3xl font-extrabold text-error leading-none">{{ $totalAlpa }}</p>
                    <p class="text-xs sm:text-sm font-bold text-on-surface mt-1.5">Tidak Hadir</p>
                    <p class="text-[11px] text-on-surface-variant mt-0.5">Tanpa keterangan</p>
                </div>
            </div>
        </section>

        <!-- 3. Action Buttons Section (Mobile Friendly Quick Shortcuts) -->
        <section aria-label="Menu Aksi Cepat" class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
            <a href="{{ route('peserta.pelatihan') }}"
               class="min-h-[52px] px-5 py-3.5 rounded-2xl bg-primary text-on-primary shadow-xs flex items-center justify-between hover:bg-primary-container active:scale-[0.99] transition">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center text-on-primary">
                        <span class="material-symbols-outlined text-[20px]">menu_book</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs sm:text-sm font-bold leading-tight">Pelatihan Yang Diikuti</span>
                        <span class="text-[11px] text-on-primary-container">Lihat jadwal sesi & materi</span>
                    </div>
                </div>
                <span class="material-symbols-outlined text-[20px]">chevron_right</span>
            </a>

            <a href="{{ route('peserta.cari-pelatihan') }}"
               class="min-h-[52px] px-5 py-3.5 rounded-2xl bg-surface-container-lowest text-on-surface shadow-xs border border-outline-variant/30 flex items-center justify-between hover:bg-surface-container active:scale-[0.99] transition">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-primary-fixed text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">search</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs sm:text-sm font-bold leading-tight">Cari Pelatihan Baru</span>
                        <span class="text-[11px] text-on-surface-variant">Daftar ke kelas keterampilan terbuka</span>
                    </div>
                </div>
                <span class="material-symbols-outlined text-[20px] text-on-surface-variant">arrow_forward</span>
            </a>
        </section>

        <!-- 4. Sesi Hari Ini: Class Schedule Focus -->
        <section class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-xs p-5 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-outline-variant/20 mb-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-secondary"></span>
                        <h2 class="text-base sm:text-lg font-bold text-on-surface">Jadwal Kelas Hari Ini</h2>
                    </div>
                    <p class="text-xs text-on-surface-variant mt-0.5">Pastikan Anda berada di kelas dan scan QR yang ditampilkan oleh tutor.</p>
                </div>
                <span class="text-xs font-semibold text-on-surface-variant bg-surface-container px-3 py-1 rounded-xl self-start sm:self-auto">
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>
            </div>

            @if($sesiHariIni->isEmpty())
                <div class="text-center py-10 px-4 rounded-xl bg-surface-container-low/50 border border-dashed border-outline-variant/40">
                    <div class="w-12 h-12 rounded-2xl bg-surface-container flex items-center justify-center mx-auto text-on-surface-variant mb-3">
                        <span class="material-symbols-outlined text-[28px]">event_available</span>
                    </div>
                    <p class="text-sm font-bold text-on-surface">Tidak ada jadwal sesi kelas hari ini</p>
                    <p class="text-xs text-on-surface-variant mt-1">Silakan periksa jadwal sesi berikutnya pada menu Pelatihan Saya.</p>
                </div>
            @else
                <div class="flex flex-col gap-3.5">
                    @foreach($sesiHariIni as $sesi)
                        @php
                            $userPresensi = $sesi->presensis->first();
                            $now = now();
                            $isWithinWindow = $sesi->isWithinAttendanceWindow($now);
                            $isBefore = $sesi->isBeforeWindow($now);
                            $isAfter = $sesi->isAfterWindow($now);
                        @endphp
                        <div class="p-4 sm:p-5 rounded-2xl border {{ $userPresensi ? 'border-secondary/40 bg-secondary-container/20' : ($isWithinWindow ? 'border-primary/40 bg-primary-fixed/20' : 'border-outline-variant/30 bg-surface-container-low/60') }} flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition">
                            <div class="space-y-1.5">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-xs font-bold text-primary">{{ $sesi->pelatihan->nama }}</span>
                                    <span class="text-outline text-xs">•</span>
                                    <span class="text-xs text-on-surface-variant flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px] text-primary">location_on</span>
                                        {{ $sesi->lokasi }}
                                    </span>
                                </div>
                                <h3 class="text-base font-bold text-on-surface">{{ $sesi->judul_sesi }}</h3>
                                <p class="text-xs text-on-surface-variant flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px]">schedule</span>
                                    <span>{{ substr((string)$sesi->jam_mulai, 0, 5) }} – {{ substr((string)$sesi->jam_selesai, 0, 5) }} WIB</span>
                                    <span class="text-outline">•</span>
                                    <span class="text-[11px] text-on-surface-variant">(Toleransi {{ $sesi->batas_terlambat_menit }} mnt)</span>
                                </p>
                            </div>

                            <div class="flex items-center gap-3 shrink-0 self-start sm:self-auto">
                                @if($userPresensi)
                                    @php
                                        $st = strtolower($userPresensi->status);
                                    @endphp
                                    <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold shadow-xs
                                        {{ $st === 'hadir' ? 'bg-secondary text-on-secondary' :
                                           ($st === 'terlambat' ? 'bg-amber-500 text-white' :
                                           ($st === 'izin' ? 'bg-primary text-on-primary' :
                                           ($st === 'sakit' ? 'bg-purple-700 text-white' : 'bg-error text-on-error'))) }}">
                                        <span class="material-symbols-outlined text-[18px]">
                                            {{ $st === 'alpa' ? 'cancel' : 'verified' }}
                                        </span>
                                        <span class="uppercase">{{ $userPresensi->status }} ({{ $userPresensi->waktu_server ? $userPresensi->waktu_server->format('H:i') : '-' }})</span>
                                    </div>
                                @elseif($isWithinWindow)
                                    <a href="{{ route('peserta.presensi.pindai', $sesi) }}"
                                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary-container text-on-primary font-bold text-xs rounded-xl shadow-xs transition active:scale-[0.98]">
                                        <span class="material-symbols-outlined text-[20px]">qr_code_scanner</span>
                                        <span>Pindai QR / Kode</span>
                                    </a>
                                @elseif($isBefore)
                                    <span class="text-xs font-semibold text-on-surface-variant bg-surface-container px-3 py-2 rounded-xl border border-outline-variant/30 flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[16px]">hourglass_top</span>
                                        <span>Buka jam {{ $sesi->window_buka->format('H:i') }}</span>
                                    </span>
                                @else
                                    <span class="text-xs font-bold text-error bg-error-container px-3 py-2 rounded-xl border border-error/30 flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[16px]">cancel</span>
                                        <span>ALPA (Tidak Hadir)</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

    </div>
</x-app-layout>
