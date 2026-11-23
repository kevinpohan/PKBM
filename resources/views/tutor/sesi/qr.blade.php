<!DOCTYPE html>
<html lang="id" class="h-full bg-on-background">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>QR Sesi & Live Monitoring — {{ $sesi->judul_sesi }}</title>

    <!-- Google Fonts & Material Symbols -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <!-- Tailwind CSS with Stitch Design Tokens -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#00288e",
                        "primary-container": "#1e40af",
                        "on-primary": "#ffffff",
                        "on-primary-container": "#a8b8ff",
                        "primary-fixed": "#dde1ff",
                        "on-primary-fixed": "#001453",
                        "primary-fixed-dim": "#b8c4ff",
                        "on-primary-fixed-variant": "#173bab",

                        "secondary": "#006c4a",
                        "secondary-container": "#82f5c1",
                        "on-secondary": "#ffffff",
                        "on-secondary-container": "#00714e",
                        "secondary-fixed": "#85f8c4",
                        "on-secondary-fixed": "#002114",
                        "secondary-fixed-dim": "#68dba9",
                        "on-secondary-fixed-variant": "#005137",

                        "tertiary": "#532a00",
                        "tertiary-container": "#743d00",
                        "on-tertiary": "#ffffff",
                        "on-tertiary-container": "#ffa85d",
                        "tertiary-fixed": "#ffdcc3",
                        "on-tertiary-fixed": "#2f1500",
                        "tertiary-fixed-dim": "#ffb77d",
                        "on-tertiary-fixed-variant": "#6e3900",

                        "error": "#ba1a1a",
                        "error-container": "#ffdad6",
                        "on-error": "#ffffff",
                        "on-error-container": "#93000a",

                        "background": "#faf8ff",
                        "on-background": "#131b2e",
                        "surface": "#faf8ff",
                        "on-surface": "#131b2e",
                        "surface-variant": "#dae2fd",
                        "on-surface-variant": "#444653",
                        "outline": "#757684",
                        "outline-variant": "#c4c5d5",

                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#f2f3ff",
                        "surface-container": "#eaedff",
                        "surface-container-high": "#e2e7ff",
                        "surface-container-highest": "#dae2fd",
                        "surface-dim": "#d2d9f4",
                        "surface-bright": "#faf8ff",
                        "surface-tint": "#3755c3",

                        "inverse-surface": "#283044",
                        "inverse-on-surface": "#eef0ff",
                        "inverse-primary": "#b8c4ff"
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .material-symbols-outlined.fill {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="h-full flex flex-col font-sans text-surface antialiased bg-on-background"
      x-data="qrAndLiveHandler({{ $sesi->id }}, {{ $remainingSeconds }})"
      x-init="init()">

    <!-- Top Bar Navigation (Responsive Desktop & Mobile) -->
    <header class="px-4 sm:px-6 py-3 flex items-center justify-between border-b border-white/10 bg-inverse-surface/90 backdrop-blur-xl z-30">
        <div class="flex items-center gap-3">
            <a href="{{ route('tutor.dashboard') }}"
               class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-surface text-xs font-bold flex items-center gap-1.5 transition">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                <span class="hidden sm:inline">Dashboard</span>
            </a>
            <div>
                <span class="text-xs font-bold text-primary-fixed block uppercase tracking-wider">{{ $sesi->pelatihan->nama }}</span>
                <h1 class="text-sm sm:text-base font-extrabold text-surface leading-tight">{{ $sesi->judul_sesi }}</h1>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <!-- Mobile View Switcher Tab (only visible on mobile/tablet) -->
            <div class="flex lg:hidden bg-white/10 rounded-xl p-0.5 text-xs font-bold">
                <button type="button" @click="mobileView = 'qr'"
                        :class="mobileView === 'qr' ? 'bg-primary text-on-primary shadow-xs' : 'text-surface-variant hover:text-surface'"
                        class="px-2.5 py-1 rounded-lg transition">
                    QR Sesi
                </button>
                <button type="button" @click="mobileView = 'live'"
                        :class="mobileView === 'live' ? 'bg-secondary text-on-secondary shadow-xs' : 'text-surface-variant hover:text-surface'"
                        class="px-2.5 py-1 rounded-lg transition flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-secondary-fixed animate-ping"></span>
                    <span>Live (<span x-text="totalMasuk"></span>)</span>
                </button>
            </div>

            <!-- Fullscreen Button -->
            <button type="button" @click="toggleFullscreen()"
                    class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-surface transition"
                    title="Layar Penuh (F11)">
                <span class="material-symbols-outlined text-[20px]">fullscreen</span>
            </button>
        </div>
    </header>

    <!-- Main Dual Screen: Left (QR & Code) + Right (Live Monitoring Stream) -->
    <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full">

        @if(!$isWithinWindow)
            <div class="mb-5 p-4 rounded-2xl bg-amber-500/20 border border-amber-500/40 text-amber-200 text-xs w-full flex flex-col sm:flex-row items-center justify-between gap-3 shadow-lg">
                <div class="text-left">
                    <div class="font-bold text-amber-300 text-sm flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px]">warning</span>
                        <span>Sesi Di Luar Jendela Presensi</span>
                    </div>
                    <div class="text-[11px] text-amber-200/90 mt-0.5">Jadwal tercatat: {{ substr((string)$sesi->jam_mulai, 0, 5) }} – {{ substr((string)$sesi->jam_selesai, 0, 5) }} WIB. Peserta hanya dapat presensi saat jendela aktif dibuka.</div>
                </div>
                <button type="button" @click="bukaModalOpen = true"
                        class="px-4 py-2 bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold text-xs rounded-xl shadow transition cursor-pointer shrink-0">
                    ⚡ Buka Sekarang
                </button>
            </div>

            <!-- Modal Pop Up Konfirmasi Buka Sesi (Tanpa Browser Alert) -->
            <div x-show="bukaModalOpen" x-cloak
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/75 backdrop-blur-xs"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                <div class="bg-surface-container-lowest text-on-surface rounded-3xl p-6 sm:p-7 max-w-md w-full shadow-2xl border border-outline-variant/30 flex flex-col gap-4 text-left"
                     @click.outside="bukaModalOpen = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[28px]">bolt</span>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-on-surface">Buka Presensi Sesi Sekarang</h3>
                            <p class="text-xs text-on-surface-variant">Aktifkan jendela presensi dan rotasi token QR</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-surface-container-low border border-outline-variant/30 text-xs text-on-surface flex flex-col gap-2">
                        <div class="font-bold text-on-surface">{{ $sesi->judul_sesi }}</div>
                        <div class="text-[11px] text-on-surface-variant">
                            Jadwal terdaftar: <span class="font-semibold text-on-surface">{{ substr((string)$sesi->jam_mulai, 0, 5) }} – {{ substr((string)$sesi->jam_selesai, 0, 5) }} WIB</span>.
                        </div>
                        <div class="text-[11px] text-on-surface-variant leading-relaxed">
                            Jam sesi akan disesuaikan otomatis mulai sekarang hingga <strong>2 jam ke depan</strong>. Peserta yang sebelumnya terlewat jam presensi dapat langsung memindai QR dan mencatatkan kehadirannya.
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-1">
                        <button type="button" @click="bukaModalOpen = false"
                                class="px-4 py-2.5 rounded-xl bg-surface-container hover:bg-surface-container-high text-on-surface text-xs font-bold transition cursor-pointer">
                            Batal
                        </button>
                        <form action="{{ route('tutor.sesi.activate-now', $sesi) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-extrabold text-xs shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                                <span class="material-symbols-outlined text-[18px]">bolt</span>
                                <span>Ya, Buka Sesi Sekarang</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- ============================================== -->
            <!-- LEFT COLUMN: HERO DYNAMIC QR & MANUAL CODE     -->
            <!-- ============================================== -->
            <div class="lg:col-span-7 flex flex-col gap-4"
                 :class="{ 'hidden lg:flex': mobileView === 'live' }">
                
                <div class="bg-surface-container-lowest rounded-3xl p-5 sm:p-7 shadow-2xl border-4 border-white/20 flex flex-col items-center text-on-surface relative overflow-hidden">
                    
                    <!-- Top Strip -->
                    <div class="w-full flex items-center justify-between pb-3 mb-2 border-b border-outline-variant/20">
                        <div class="flex items-center gap-1.5 text-primary text-xs font-bold">
                            <span class="material-symbols-outlined text-[18px] text-secondary">verified_user</span>
                            <span>QR Presensi Dinamis</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-xs text-on-surface-variant bg-surface-container px-2.5 py-1 rounded-full font-semibold">
                            <span class="material-symbols-outlined text-[16px] text-primary">sync</span>
                            <span>Rotasi Tiap 5 Menit</span>
                        </div>
                    </div>

                    <!-- Giant QR Container -->
                    <div class="relative p-3 sm:p-5 bg-surface-container-lowest rounded-2xl flex flex-col items-center justify-center">
                        <div id="qrContainer" class="w-64 h-64 sm:w-80 sm:h-80 md:w-88 md:h-88 flex items-center justify-center" x-html="qrSvg">
                            {!! $qrSvg !!}
                        </div>
                    </div>

                    <!-- 5-Minute Timer Countdown Widget -->
                    <div class="w-full mt-3 p-3.5 rounded-2xl bg-surface-container-low border border-outline-variant/30 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary-fixed text-primary flex items-center justify-center">
                                <span class="material-symbols-outlined text-[20px]">timer</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-on-surface">Rotasi QR dalam: <strong class="text-primary font-mono text-sm" x-text="formatTimer(countdown)">05:00</strong></span>
                                <span class="text-[11px] text-on-surface-variant">Toleransi jeda 15 detik untuk koneksi peserta</span>
                            </div>
                        </div>
                        <button type="button" @click="fetchNewQr()"
                                class="p-2 text-primary hover:bg-surface-container rounded-xl transition" title="Segarkan QR Sekarang">
                            <span class="material-symbols-outlined text-[22px]">refresh</span>
                        </button>
                    </div>

                    <!-- 6-Digit Manual Code Box -->
                    <div class="w-full mt-3 p-4 rounded-2xl bg-surface-container border border-outline-variant/30 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-surface-container-lowest text-primary flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[20px]">dialpad</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[11px] font-bold text-on-surface uppercase tracking-wider">Kode Manual Alternatif</span>
                                <span class="text-[11px] text-on-surface-variant">Jika kamera peserta bermasalah</span>
                            </div>
                        </div>
                        <div class="px-4 py-1.5 rounded-xl bg-surface-container-lowest border-2 border-primary/20 shadow-xs">
                            <span class="font-mono text-2xl sm:text-3xl font-black text-primary tracking-[0.25em]" x-text="kodeManual">
                                {{ $sesi->kode_manual }}
                            </span>
                        </div>
                    </div>

                    <!-- Footer of QR card -->
                    <div class="w-full mt-3 pt-3 border-t border-outline-variant/20 flex items-center justify-between text-[11px] text-on-surface-variant">
                        <span class="font-bold text-primary">PKBM Karawang</span>
                        <span class="font-medium flex items-center gap-1">
                            <span class="material-symbols-outlined text-[15px] text-primary">location_on</span>
                            {{ $sesi->lokasi }}
                        </span>
                    </div>
                </div>

            </div>

            <!-- ============================================== -->
            <!-- RIGHT COLUMN: LIVE ATTENDANCE MONITOR & STREAM -->
            <!-- ============================================== -->
            <div class="lg:col-span-5 flex flex-col gap-4"
                 :class="{ 'hidden lg:flex': mobileView === 'qr' }">

                <!-- 1. Real-time Attendance Gauge Card -->
                <div class="bg-surface-container-lowest rounded-3xl p-5 sm:p-6 shadow-xl border border-outline-variant/30 text-on-surface flex flex-col gap-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[22px]">groups</span>
                            <span class="text-sm font-extrabold text-on-surface">Partisipasi Kelas</span>
                        </div>
                        <span class="text-xs font-bold text-secondary bg-secondary-container px-2.5 py-0.5 rounded-full"
                              x-text="`${ratio}% Hadir`">
                            {{ $ratio }}% Hadir
                        </span>
                    </div>

                    <div class="flex items-baseline justify-between">
                        <div class="flex items-baseline gap-1.5">
                            <span class="text-3xl sm:text-4xl font-black text-primary" x-text="totalMasuk">{{ $total_masuk }}</span>
                            <span class="text-sm text-on-surface-variant font-bold">/ <span x-text="totalEnrolled">{{ $total_enrolled }}</span></span>
                            <span class="text-xs text-on-surface-variant font-medium ml-1">Warga Belajar</span>
                        </div>
                        <span class="text-[11px] text-on-surface-variant">Auto-sync 4 dtk</span>
                    </div>

                    <!-- High Contrast Progress Bar -->
                    <div class="w-full h-3 bg-surface-container rounded-full overflow-hidden flex">
                        <div class="h-full bg-secondary transition-all duration-700 rounded-full"
                             :style="'width: ' + ratio + '%'"></div>
                    </div>

                    <!-- Status Breakdown Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-1 text-center">
                        <div class="p-2 rounded-xl bg-secondary-container/30 border border-secondary/20 flex flex-col">
                            <span class="text-xs font-black text-secondary" x-text="countHadir">{{ $count_hadir }}</span>
                            <span class="text-[10px] text-on-surface-variant">Tepat Waktu</span>
                        </div>
                        <div class="p-2 rounded-xl bg-amber-100/50 border border-amber-200 flex flex-col">
                            <span class="text-xs font-black text-amber-700" x-text="countTerlambat">{{ $count_terlambat }}</span>
                            <span class="text-[10px] text-on-surface-variant">Terlambat</span>
                        </div>
                        <div class="p-2 rounded-xl bg-primary-fixed/50 border border-primary/20 flex flex-col">
                            <span class="text-xs font-black text-primary" x-text="countIzin + countSakit">{{ $count_izin + $count_sakit }}</span>
                            <span class="text-[10px] text-on-surface-variant">Izin/Sakit</span>
                        </div>
                        <div class="p-2 rounded-xl bg-error-container/40 border border-error/20 flex flex-col">
                            <span class="text-xs font-black text-error" x-text="countBelumAbsen">{{ $count_belum_absen }}</span>
                            <span class="text-[10px] text-on-surface-variant">Belum Hadir</span>
                        </div>
                    </div>
                </div>

                <!-- 2. Live Participant Arrival Stream -->
                <div class="bg-surface-container-lowest rounded-3xl p-5 sm:p-6 shadow-xl border border-outline-variant/30 text-on-surface flex flex-col gap-3">
                    <div class="flex items-center justify-between pb-2 border-b border-outline-variant/20">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-secondary animate-pulse"></span>
                            <span class="text-sm font-extrabold text-on-surface">Arus Presensi Terkini</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" @click="toggleSound()"
                                    class="text-[11px] font-semibold text-on-surface-variant hover:text-on-surface flex items-center gap-1 bg-surface-container px-2.5 py-1 rounded-lg">
                                <span class="material-symbols-outlined text-[16px]" :class="soundEnabled ? 'text-secondary' : 'text-outline'" x-text="soundEnabled ? 'volume_up' : 'volume_off'"></span>
                                <span x-text="soundEnabled ? 'Bunyi: ON' : 'Bunyi: OFF'"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Search / Filter in stream -->
                    <div class="flex items-center gap-2">
                        <div class="relative flex-1">
                            <input type="text" x-model="searchQuery" placeholder="Cari nama peserta..."
                                   class="w-full pl-8 pr-3 py-1.5 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary focus:outline-none">
                            <span class="material-symbols-outlined absolute left-2 top-1.5 text-[18px] text-outline pointer-events-none">search</span>
                        </div>
                        <select x-model="filterStatus"
                                class="px-2.5 py-1.5 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface font-semibold focus:ring-2 focus:ring-primary focus:outline-none">
                            <option value="">Semua</option>
                            <option value="hadir">Hadir</option>
                            <option value="terlambat">Terlambat</option>
                            <option value="belum_absen">Belum</option>
                        </select>
                    </div>

                    <!-- Attendees Live Scroll List -->
                    <div class="flex flex-col gap-2 max-h-[380px] overflow-y-auto pr-1">
                        <template x-for="item in filteredAttendees" :key="item.user_id">
                            <div class="p-3 bg-surface-container-low/70 hover:bg-surface-container transition rounded-2xl flex items-center justify-between border border-outline-variant/20 shadow-xs">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs shrink-0"
                                         :class="item.status === 'hadir' ? 'bg-secondary-container text-on-secondary-container' : (item.status === 'terlambat' ? 'bg-amber-100 text-amber-900' : 'bg-surface-container-high text-on-surface-variant')">
                                        <span x-text="item.nama.substring(0, 2).toUpperCase()"></span>
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <span class="font-bold text-xs text-on-surface truncate" x-text="item.nama"></span>
                                        <span class="text-[11px] text-on-surface-variant font-mono" x-text="item.nomor_induk || '-'"></span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <div class="flex flex-col items-end">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase"
                                              :class="{
                                                  'bg-secondary-container text-on-secondary-container': item.status === 'hadir',
                                                  'bg-amber-100 text-amber-900': item.status === 'terlambat',
                                                  'bg-primary-fixed text-on-primary-fixed': item.status === 'izin',
                                                  'bg-purple-100 text-purple-900': item.status === 'sakit',
                                                  'bg-error-container text-on-error-container': item.status === 'alpa',
                                                  'bg-surface-container text-on-surface-variant': item.status === 'belum_absen'
                                              }"
                                              x-text="item.status.replace('_', ' ')">
                                        </span>
                                        <span class="text-[10px] text-on-surface-variant font-mono mt-0.5" x-text="item.waktu || '-'"></span>
                                    </div>

                                    <button type="button" @click="openKoreksiModal(item)"
                                            class="p-1 rounded-lg bg-surface-container-lowest hover:bg-primary-fixed text-primary transition"
                                            title="Koreksi Manual">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <div x-show="filteredAttendees.length === 0" class="py-8 text-center text-xs text-on-surface-variant italic">
                            Tidak ada peserta yang cocok dengan filter.
                        </div>
                    </div>

                    <div class="pt-2 border-t border-outline-variant/20 flex items-center justify-between text-[11px] text-on-surface-variant">
                        <span>Pembaruan: <span class="font-mono font-semibold text-on-surface" x-text="lastUpdated">{{ $last_updated }}</span></span>
                        <a href="{{ route('tutor.sesi.live', $sesi) }}" class="text-primary hover:underline font-bold flex items-center gap-0.5">
                            <span>Tabel Lengkap</span>
                            <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </main>

    <!-- Modal Koreksi Presensi (Stitch Modal Pattern) -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-xs" @click="showModal = false"></div>
        
        <div class="relative bg-surface-container-lowest rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-outline-variant/30 z-10 text-on-surface">
            <div class="flex items-center justify-between pb-3 border-b border-outline-variant/20 mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-primary-fixed text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">edit_note</span>
                    </div>
                    <h3 class="text-base font-bold text-on-surface">Koreksi Presensi Cepat</h3>
                </div>
                <button type="button" @click="showModal = false" class="p-1 rounded-lg text-on-surface-variant hover:bg-surface-container">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <form method="POST" action="{{ route('tutor.koreksi.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="sesi_id" value="{{ $sesi->id }}">
                <input type="hidden" name="user_id" :value="modalItem ? modalItem.user_id : ''">

                <div class="p-3 rounded-xl bg-surface-container-low border border-outline-variant/20">
                    <span class="text-[11px] text-on-surface-variant block">Warga Belajar:</span>
                    <span class="text-sm font-bold text-on-surface" x-text="modalItem ? modalItem.nama : ''"></span>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-on-surface">Ubah Status Menjadi:</label>
                    <select name="status_sesudah" required
                            class="w-full px-3.5 py-2.5 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs font-bold text-on-surface focus:ring-2 focus:ring-primary focus:outline-none">
                        <option value="hadir">HADIR (Tepat Waktu)</option>
                        <option value="terlambat">TERLAMBAT</option>
                        <option value="izin">IZIN</option>
                        <option value="sakit">SAKIT</option>
                        <option value="alpa">ALPA</option>
                    </select>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-on-surface flex items-center justify-between">
                        <span>Alasan Koreksi</span>
                        <span class="text-[11px] text-error font-semibold">* Wajib Audit</span>
                    </label>
                    <textarea name="alasan" required rows="3" minlength="5"
                              placeholder="Contoh: Peserta hadir langsung di kelas namun kamera HP rusak, atau izin via WA."
                              class="w-full p-3 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary focus:outline-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-outline-variant/20">
                    <button type="button" @click="showModal = false"
                            class="px-4 py-2.5 bg-surface-container hover:bg-surface-container-high text-on-surface text-xs font-bold rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 bg-primary hover:bg-primary-container text-on-primary text-xs font-bold rounded-xl shadow-xs transition">
                        Simpan Koreksi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script for Combined QR rotation & Live Monitoring Polling -->
    <script>
        function qrAndLiveHandler(sesiId, initialRemaining) {
            return {
                sesiId: sesiId,
                bukaModalOpen: false,
                mobileView: 'qr', // 'qr' or 'live' on small screens
                countdown: initialRemaining > 0 ? initialRemaining : 300,
                qrSvg: `{!! addslashes($qrSvg) !!}`,
                kodeManual: '{{ $sesi->kode_manual }}',
                timerInterval: null,
                pollingInterval: null,
                soundEnabled: true,

                // Live data
                totalEnrolled: {{ $total_enrolled ?? 0 }},
                countHadir: {{ $count_hadir ?? 0 }},
                countTerlambat: {{ $count_terlambat ?? 0 }},
                countIzin: {{ $count_izin ?? 0 }},
                countSakit: {{ $count_sakit ?? 0 }},
                countAlpa: {{ $count_alpa ?? 0 }},
                countBelumAbsen: {{ $count_belum_absen ?? 0 }},
                totalMasuk: {{ $total_masuk ?? 0 }},
                ratio: {{ $ratio ?? 0 }},
                attendees: @json($attendees ?? []),
                lastUpdated: '{{ $last_updated ?? now()->format("H:i:s") }}',
                searchQuery: '',
                filterStatus: '',
                showModal: false,
                modalItem: null,

                init() {
                    this.startCountdown();
                    this.startLivePolling();
                },

                startCountdown() {
                    if (this.timerInterval) clearInterval(this.timerInterval);
                    this.timerInterval = setInterval(() => {
                        this.countdown--;
                        if (this.countdown <= 0) {
                            this.fetchNewQr();
                        }
                    }, 1000);
                },

                startLivePolling() {
                    if (this.pollingInterval) clearInterval(this.pollingInterval);
                    this.pollingInterval = setInterval(() => {
                        this.fetchLiveData();
                    }, 4000);
                },

                formatTimer(seconds) {
                    const m = Math.floor(seconds / 60);
                    const s = seconds % 60;
                    return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
                },

                async fetchNewQr() {
                    try {
                        const response = await fetch(`/tutor/sesi/${this.sesiId}/token-json`);
                        if (response.ok) {
                            const data = await response.json();
                            this.qrSvg = data.qr_svg;
                            this.kodeManual = data.kode_manual;
                            this.countdown = data.remaining_seconds || 300;
                        }
                    } catch (e) {
                        console.error('Failed to rotate QR:', e);
                        this.countdown = 10;
                    }
                },

                async fetchLiveData() {
                    try {
                        const res = await fetch(`/tutor/sesi/${this.sesiId}/live-data`);
                        if (res.ok) {
                            const data = await res.json();
                            const prevTotal = this.totalMasuk;
                            this.totalEnrolled = data.total_enrolled;
                            this.countHadir = data.count_hadir;
                            this.countTerlambat = data.count_terlambat;
                            this.countIzin = data.count_izin;
                            this.countSakit = data.count_sakit;
                            this.countAlpa = data.count_alpa;
                            this.countBelumAbsen = data.count_belum_absen;
                            this.totalMasuk = data.total_masuk;
                            this.ratio = data.ratio;
                            this.attendees = data.attendees;
                            this.lastUpdated = data.last_updated;

                            // Play subtle chime when new student arrives
                            if (this.soundEnabled && data.total_masuk > prevTotal && prevTotal > 0) {
                                this.playChime();
                            }
                        }
                    } catch (e) {
                        console.error('Polling error', e);
                    }
                },

                playChime() {
                    try {
                        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                        const osc = audioCtx.createOscillator();
                        const gain = audioCtx.createGain();
                        osc.connect(gain);
                        gain.connect(audioCtx.destination);
                        osc.type = 'sine';
                        osc.frequency.setValueAtTime(587.33, audioCtx.currentTime); // D5
                        osc.frequency.setValueAtTime(880, audioCtx.currentTime + 0.1); // A5
                        gain.gain.setValueAtTime(0.1, audioCtx.currentTime);
                        gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.4);
                        osc.start();
                        osc.stop(audioCtx.currentTime + 0.4);
                    } catch (err) {}
                },

                toggleSound() {
                    this.soundEnabled = !this.soundEnabled;
                },

                get filteredAttendees() {
                    return this.attendees.filter(item => {
                        const matchStatus = !this.filterStatus || item.status === this.filterStatus;
                        const matchQuery = !this.searchQuery ||
                            item.nama.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            item.nomor_induk.toLowerCase().includes(this.searchQuery.toLowerCase());
                        return matchStatus && matchQuery;
                    });
                },

                openKoreksiModal(item) {
                    this.modalItem = item;
                    this.showModal = true;
                },

                toggleFullscreen() {
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen().catch(() => {});
                    } else {
                        if (document.exitFullscreen) {
                            document.exitFullscreen().catch(() => {});
                        }
                    }
                }
            }
        }
    </script>
</body>
</html>
