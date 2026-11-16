<x-app-layout title="Dashboard Warga Belajar">
    <div class="space-y-6">

        <!-- Welcome Banner (FR-03) -->
        <div class="bg-gradient-to-r from-blue-700 via-indigo-700 to-blue-800 rounded-2xl p-6 sm:p-8 text-white shadow-lg shadow-blue-900/10 relative overflow-hidden">
            <div class="relative z-10 max-w-2xl">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white backdrop-blur-md mb-3 border border-white/20">
                    Sistem Presensi Digital PKBM
                </span>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Selamat datang, {{ $user->nama }}! 👋</h1>
                <p class="text-blue-100 text-sm mt-1.5 leading-relaxed">
                    Pastikan Anda selalu memindai QR sesi pada saat pembelajaran dimulai untuk mencatat kehadiran tepat waktu.
                </p>
                <div class="flex flex-wrap items-center gap-3 mt-5">
                    <a href="{{ route('peserta.pelatihan') }}" class="px-4 py-2 bg-white text-blue-700 font-semibold text-xs rounded-xl shadow-xs hover:bg-blue-50 transition cursor-pointer">
                        Pelatihan Yang Diikuti
                    </a>
                    <a href="{{ route('peserta.cari-pelatihan') }}" class="px-4 py-2 bg-blue-600/60 hover:bg-blue-600/80 text-white font-semibold text-xs rounded-xl border border-white/30 backdrop-blur-md transition cursor-pointer">
                        Cari Pelatihan Baru &rarr;
                    </a>
                </div>
            </div>
            <!-- Decorative circle -->
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/5 rounded-full pointer-events-none"></div>
        </div>

        <!-- Metric Stat Cards (FR-02, FR-03) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
                <div class="flex items-center justify-between text-slate-500 mb-2">
                    <span class="text-xs font-semibold">Pelatihan Aktif</span>
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900">{{ $totalPelatihan }}</div>
                <span class="text-[11px] text-slate-400">Program diikuti</span>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
                <div class="flex items-center justify-between text-slate-500 mb-2">
                    <span class="text-xs font-semibold">Total Hadir</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-emerald-600">{{ $totalHadir }}</div>
                <span class="text-[11px] text-slate-400">Tepat waktu & terlambat</span>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
                <div class="flex items-center justify-between text-slate-500 mb-2">
                    <span class="text-xs font-semibold">Izin & Sakit</span>
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-amber-600">{{ $totalIzinSakit }}</div>
                <span class="text-[11px] text-slate-400">Tercatat resmi</span>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
                <div class="flex items-center justify-between text-slate-500 mb-2">
                    <span class="text-xs font-semibold">Alpa / Tidak Hadir</span>
                    <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-rose-600">{{ $totalAlpa }}</div>
                <span class="text-[11px] text-slate-400">Tanpa keterangan</span>
            </div>

        </div>

        <!-- Sesi Hari Ini Section -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Sesi Kelas Hari Ini</h2>
                    <p class="text-xs text-slate-500">Jadwal pembelajaran yang berlangsung hari ini.</p>
                </div>
                <span class="text-xs font-medium text-slate-500 bg-slate-100 px-3 py-1 rounded-lg">
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>
            </div>

            @if($sesiHariIni->isEmpty())
                <div class="text-center py-10 px-4">
                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-700">Tidak ada jadwal sesi hari ini</p>
                    <p class="text-xs text-slate-400 mt-1">Silakan periksa jadwal mendatang pada menu Pelatihan Saya.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($sesiHariIni as $sesi)
                        @php
                            $userPresensi = $sesi->presensis->first();
                            $now = now();
                            $isWithinWindow = $sesi->isWithinAttendanceWindow($now);
                            $isBefore = $sesi->isBeforeWindow($now);
                            $isAfter = $sesi->isAfterWindow($now);
                        @endphp
                        <div class="p-4 rounded-xl border {{ $userPresensi ? 'border-emerald-200 bg-emerald-50/40' : ($isWithinWindow ? 'border-blue-300 bg-blue-50/50' : 'border-slate-200 bg-slate-50') }} flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-blue-700">{{ $sesi->pelatihan->nama }}</span>
                                    <span class="text-[10px] text-slate-400">&bull;</span>
                                    <span class="text-xs text-slate-500">{{ $sesi->lokasi }}</span>
                                </div>
                                <h3 class="text-sm font-bold text-slate-900">{{ $sesi->judul_sesi }}</h3>
                                <p class="text-xs text-slate-600 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    {{ substr((string)$sesi->jam_mulai, 0, 5) }} – {{ substr((string)$sesi->jam_selesai, 0, 5) }} WIB
                                    <span class="text-[11px] text-slate-400">(Toleransi keterlambatan {{ $sesi->batas_terlambat_menit }} mnt)</span>
                                </p>
                            </div>

                            <div class="flex items-center gap-3 shrink-0">
                                @if($userPresensi)
                                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-100 text-emerald-800 text-xs font-semibold border border-emerald-200">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        {{ strtoupper($userPresensi->status) }} ({{ $userPresensi->waktu_server->format('H:i') }})
                                    </div>
                                @elseif($isWithinWindow)
                                    <a href="{{ route('peserta.presensi.pindai', $sesi) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-sm transition transform active:scale-95">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                                        Pindai QR / Kode
                                    </a>
                                @elseif($isBefore)
                                    <span class="text-xs font-medium text-amber-700 bg-amber-50 px-3 py-1.5 rounded-lg border border-amber-200">
                                        Dibuka 15 mnt sebelum mulai ({{ $sesi->window_buka->format('H:i') }})
                                    </span>
                                @else
                                    <span class="text-xs font-medium text-rose-700 bg-rose-50 px-3 py-1.5 rounded-lg border border-rose-200">
                                        Sesi Selesai (Tidak Hadir)
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
