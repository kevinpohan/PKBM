<!DOCTYPE html>
<html lang="id" class="h-full bg-background">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Presensi Digital PKBM' }} — PKBM Karawang</title>

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

    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
<body class="bg-background font-sans text-on-surface antialiased flex flex-col min-h-screen" x-data="{ mobileSidebarOpen: false }">

    @auth
        @if(auth()->user()->isTutor())
            <!-- ========================================== -->
            <!-- TUTOR LAYOUT (DESKTOP SIDEBAR + MOBILE APP) -->
            <!-- ========================================== -->

            <!-- Desktop Sidebar -->
            <aside class="hidden lg:flex fixed left-0 top-0 h-full w-72 bg-surface-container-lowest shadow-[0_1px_8px_rgba(0,0,0,0.04)] z-50 flex-col justify-between border-r border-outline-variant/30">
                <div class="flex flex-col">
                    <!-- Brand Header -->
                    <div class="h-16 px-5 flex items-center gap-3 bg-surface-container-low border-b border-outline-variant/20">
                        <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-on-primary shadow-sm font-bold">
                            <span class="material-symbols-outlined text-[24px]">school</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-bold text-base tracking-tight text-primary leading-tight">PKBM Karawang</span>
                            <span class="text-xs text-on-surface-variant font-medium">Sistem Presensi Belajar</span>
                        </div>
                    </div>

                    <!-- Dapodik Connection Indicator -->
                    <div class="px-5 pt-4 pb-2">
                        <div class="p-2.5 rounded-xl bg-surface-container-low flex items-center justify-between border border-outline-variant/20">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                                <span class="text-xs text-on-surface font-medium">Server Presensi</span>
                            </div>
                            <span class="text-[11px] text-secondary font-bold uppercase tracking-wider bg-secondary-container/50 px-2 py-0.5 rounded-full">ONLINE</span>
                        </div>
                    </div>

                    <!-- Navigation Category -->
                    <div class="px-5 py-2">
                        <span class="text-[11px] uppercase tracking-wider text-outline font-semibold">Navigasi Tutor</span>
                    </div>

                    <!-- Navigation Menu -->
                    <nav class="flex flex-col gap-1 px-4">
                        <a href="{{ route('tutor.dashboard') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-colors font-semibold text-sm {{ request()->routeIs('tutor.dashboard') ? 'bg-primary-container text-on-primary shadow-xs' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                            <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('tutor.dashboard') ? 'fill' : '' }}">dashboard</span>
                            <span>Dashboard Utama</span>
                        </a>

                        <a href="{{ route('tutor.pelatihan.index') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-colors font-semibold text-sm {{ request()->routeIs('tutor.pelatihan.*') ? 'bg-primary-container text-on-primary shadow-xs' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                            <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('tutor.pelatihan.*') ? 'fill' : '' }}">calendar_month</span>
                            <span>Pelatihan & Sesi</span>
                        </a>

                        <a href="{{ route('tutor.koreksi.index') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-colors font-semibold text-sm {{ request()->routeIs('tutor.koreksi.*') ? 'bg-primary-container text-on-primary shadow-xs' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                            <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('tutor.koreksi.*') ? 'fill' : '' }}">fact_check</span>
                            <span>Pemantauan & Koreksi</span>
                        </a>

                        <a href="{{ route('tutor.laporan.index') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-colors font-semibold text-sm {{ request()->routeIs('tutor.laporan.*') ? 'bg-primary-container text-on-primary shadow-xs' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                            <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('tutor.laporan.*') ? 'fill' : '' }}">assignment</span>
                            <span>Rekap & Ekspor Laporan</span>
                        </a>

                        <a href="{{ route('tutor.users.index') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-colors font-semibold text-sm {{ request()->routeIs('tutor.users.*') ? 'bg-primary-container text-on-primary shadow-xs' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                            <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('tutor.users.*') ? 'fill' : '' }}">group</span>
                            <span>Kelola Pengguna</span>
                        </a>
                    </nav>
                </div>

                <!-- Sidebar Footer -->
                <div class="p-4 bg-surface-container-lowest border-t border-outline-variant/20">
                    <div class="p-3 rounded-xl bg-surface-container-low flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-secondary text-[22px]">verified</span>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-on-surface leading-tight">Paket A, B & C Verval</span>
                            <span class="text-[11px] text-on-surface-variant">Standar Kemendikbudristek</span>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Mobile Slide-Over Sidebar for Tutor -->
            <div x-show="mobileSidebarOpen" x-cloak class="fixed inset-0 z-50 lg:hidden">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-black/40 backdrop-blur-xs transition-opacity" @click="mobileSidebarOpen = false"></div>

                <!-- Slide Drawer -->
                <div class="fixed inset-y-0 left-0 w-72 bg-surface-container-lowest shadow-2xl flex flex-col justify-between z-50">
                    <div class="flex flex-col">
                        <div class="h-16 px-5 flex items-center justify-between bg-surface-container-low border-b border-outline-variant/20">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-primary flex items-center justify-center text-on-primary font-bold">
                                    <span class="material-symbols-outlined text-[20px]">school</span>
                                </div>
                                <span class="font-bold text-sm text-primary">PKBM Karawang</span>
                            </div>
                            <button @click="mobileSidebarOpen = false" class="p-1 rounded-lg text-on-surface-variant hover:bg-surface-container">
                                <span class="material-symbols-outlined text-[22px]">close</span>
                            </button>
                        </div>

                        <div class="px-5 py-3">
                            <span class="text-[11px] uppercase tracking-wider text-outline font-semibold">Navigasi Tutor</span>
                        </div>

                        <nav class="flex flex-col gap-1 px-4">
                            <a href="{{ route('tutor.dashboard') }}" @click="mobileSidebarOpen = false"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-sm {{ request()->routeIs('tutor.dashboard') ? 'bg-primary-container text-on-primary' : 'text-on-surface-variant hover:bg-surface-container' }}">
                                <span class="material-symbols-outlined text-[20px]">dashboard</span>
                                <span>Dashboard Utama</span>
                            </a>
                            <a href="{{ route('tutor.pelatihan.index') }}" @click="mobileSidebarOpen = false"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-sm {{ request()->routeIs('tutor.pelatihan.*') ? 'bg-primary-container text-on-primary' : 'text-on-surface-variant hover:bg-surface-container' }}">
                                <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                                <span>Pelatihan & Sesi</span>
                            </a>
                            <a href="{{ route('tutor.koreksi.index') }}" @click="mobileSidebarOpen = false"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-sm {{ request()->routeIs('tutor.koreksi.*') ? 'bg-primary-container text-on-primary' : 'text-on-surface-variant hover:bg-surface-container' }}">
                                <span class="material-symbols-outlined text-[20px]">fact_check</span>
                                <span>Pemantauan & Koreksi</span>
                            </a>
                            <a href="{{ route('tutor.laporan.index') }}" @click="mobileSidebarOpen = false"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-sm {{ request()->routeIs('tutor.laporan.*') ? 'bg-primary-container text-on-primary' : 'text-on-surface-variant hover:bg-surface-container' }}">
                                <span class="material-symbols-outlined text-[20px]">assignment</span>
                                <span>Rekap & Ekspor Laporan</span>
                            </a>
                            <a href="{{ route('tutor.users.index') }}" @click="mobileSidebarOpen = false"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-sm {{ request()->routeIs('tutor.users.*') ? 'bg-primary-container text-on-primary' : 'text-on-surface-variant hover:bg-surface-container' }}">
                                <span class="material-symbols-outlined text-[20px]">group</span>
                                <span>Kelola Pengguna</span>
                            </a>
                        </nav>
                    </div>

                    <div class="p-4 border-t border-outline-variant/20">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl bg-error/10 text-error font-semibold text-xs hover:bg-error/20 transition">
                                <span class="material-symbols-outlined text-[18px]">logout</span>
                                <span>Keluar Sistem</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Top Header (Desktop + Mobile) -->
            <header class="fixed top-0 left-0 right-0 lg:left-72 h-16 bg-surface-container-lowest/90 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)] z-40 flex items-center justify-between px-4 sm:px-6 lg:px-8 border-b border-outline-variant/20">
                <div class="flex items-center gap-3">
                    <!-- Mobile Hamburger -->
                    <button @click="mobileSidebarOpen = true" class="lg:hidden p-2 rounded-xl text-on-surface-variant hover:bg-surface-container focus:outline-none">
                        <span class="material-symbols-outlined text-[24px]">menu</span>
                    </button>

                    <div class="px-3 py-1 rounded-full bg-primary-fixed text-on-primary-fixed flex items-center gap-1.5 text-xs font-semibold">
                        <span class="material-symbols-outlined text-[16px]">badge</span>
                        <span class="hidden sm:inline">Portal Tutor / Instruktur</span>
                        <span class="sm:hidden">Tutor</span>
                    </div>

                    <div class="hidden sm:flex items-center gap-1.5 px-2.5 py-1 bg-surface-container rounded-lg text-xs font-semibold text-on-surface">
                        <span class="material-symbols-outlined text-on-surface-variant text-[16px]">event_available</span>
                        <span>TA 2026/2027 Ganjil</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="hidden md:flex items-center gap-1.5 text-on-surface-variant bg-surface-container-low px-2.5 py-1 rounded-lg text-xs font-medium">
                        <span class="material-symbols-outlined text-[16px] text-secondary">schedule</span>
                        <span>WIB • Real-time Sync</span>
                    </div>

                    <!-- Tutor User Profile Dropdown -->
                    <div class="relative" x-data="{ profileOpen: false }">
                        <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false" class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-surface-container transition focus:outline-none">
                            <div class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center text-xs font-bold shadow-xs">
                                {{ strtoupper(substr(auth()->user()->nama, 0, 2)) }}
                            </div>
                            <div class="hidden sm:flex flex-col text-left">
                                <span class="text-xs font-bold text-on-surface leading-tight truncate max-w-[140px]">{{ auth()->user()->nama }}</span>
                                <span class="text-[11px] text-on-surface-variant leading-tight">{{ auth()->user()->username }}</span>
                            </div>
                            <span class="material-symbols-outlined text-[18px] text-on-surface-variant">arrow_drop_down</span>
                        </button>

                        <div x-show="profileOpen" x-cloak
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             class="absolute right-0 mt-2 w-52 bg-surface-container-lowest rounded-xl shadow-xl border border-outline-variant/30 py-1.5 z-50">
                            <div class="px-4 py-2 border-b border-outline-variant/20">
                                <p class="text-[11px] text-on-surface-variant">Masuk sebagai Tutor</p>
                                <p class="text-xs font-bold text-on-surface truncate">{{ auth()->user()->nama }}</p>
                            </div>
                            <a href="{{ route('profile.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-on-surface hover:bg-surface-container transition">
                                <span class="material-symbols-outlined text-[18px] text-primary">person</span>
                                Profil & Kata Sandi
                            </a>
                            <a href="{{ route('tutor.audit-logs') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-on-surface hover:bg-surface-container transition">
                                <span class="material-symbols-outlined text-[18px] text-secondary">history_edu</span>
                                Log Audit Sistem
                            </a>
                            <div class="border-t border-outline-variant/20 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-error hover:bg-error-container/30 transition">
                                    <span class="material-symbols-outlined text-[18px] text-error">logout</span>
                                    Keluar (Logout)
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area for Tutor -->
            <main class="w-full pt-16 lg:pl-72 flex-1 pb-16 lg:pb-10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <!-- Flash Notifications -->
                    @if(session('success'))
                        <div class="mb-5 flex items-center gap-3 p-4 rounded-xl bg-secondary-container/80 text-on-secondary-container border border-secondary shadow-xs">
                            <span class="material-symbols-outlined text-[22px] text-secondary">check_circle</span>
                            <div class="text-sm font-semibold">{{ session('success') }}</div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-5 flex items-center gap-3 p-4 rounded-xl bg-error-container text-on-error-container border border-error/30 shadow-xs">
                            <span class="material-symbols-outlined text-[22px] text-error">error</span>
                            <div class="text-sm font-semibold">{{ session('error') }}</div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-5 p-4 rounded-xl bg-error-container text-on-error-container border border-error/30 shadow-xs">
                            <div class="font-bold text-sm mb-1">Periksa kembali data Anda:</div>
                            <ul class="list-disc list-inside text-xs space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </main>

            <!-- Mobile Tutor Bottom Nav Bar -->
            <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-surface-container-lowest/95 backdrop-blur-md border-t border-outline-variant/30 px-3 py-2 flex justify-around items-center shadow-lg">
                <a href="{{ route('tutor.dashboard') }}" class="flex flex-col items-center gap-0.5 text-[11px] font-semibold {{ request()->routeIs('tutor.dashboard') ? 'text-primary' : 'text-on-surface-variant' }}">
                    <span class="material-symbols-outlined text-[22px] {{ request()->routeIs('tutor.dashboard') ? 'fill' : '' }}">dashboard</span>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('tutor.pelatihan.index') }}" class="flex flex-col items-center gap-0.5 text-[11px] font-semibold {{ request()->routeIs('tutor.pelatihan.*') ? 'text-primary' : 'text-on-surface-variant' }}">
                    <span class="material-symbols-outlined text-[22px] {{ request()->routeIs('tutor.pelatihan.*') ? 'fill' : '' }}">calendar_month</span>
                    <span>Pelatihan</span>
                </a>
                <a href="{{ route('tutor.koreksi.index') }}" class="flex flex-col items-center gap-0.5 text-[11px] font-semibold {{ request()->routeIs('tutor.koreksi.*') ? 'text-primary' : 'text-on-surface-variant' }}">
                    <span class="material-symbols-outlined text-[22px] {{ request()->routeIs('tutor.koreksi.*') ? 'fill' : '' }}">fact_check</span>
                    <span>Koreksi</span>
                </a>
                <a href="{{ route('tutor.laporan.index') }}" class="flex flex-col items-center gap-0.5 text-[11px] font-semibold {{ request()->routeIs('tutor.laporan.*') ? 'text-primary' : 'text-on-surface-variant' }}">
                    <span class="material-symbols-outlined text-[22px] {{ request()->routeIs('tutor.laporan.*') ? 'fill' : '' }}">assignment</span>
                    <span>Laporan</span>
                </a>
            </nav>

        @else
            <!-- ========================================== -->
            <!-- PESERTA LAYOUT (FLEXIBLE RESPONSIVE DESKTOP & MOBILE) -->
            <!-- ========================================== -->

            <!-- Header -->
            <header class="sticky top-0 z-40 bg-surface-container-lowest/95 backdrop-blur-xl border-b border-outline-variant/20 shadow-xs">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16 items-center">
                        <!-- Brand -->
                        <div class="flex items-center gap-3">
                            <a href="{{ route('peserta.dashboard') }}" class="flex items-center gap-2.5">
                                <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-on-primary shadow-xs font-bold">
                                    <span class="material-symbols-outlined text-[22px]">school</span>
                                </div>
                                <div class="flex flex-col">
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-bold text-base text-primary leading-tight">Presensi PKBM</span>
                                        <span class="px-1.5 py-0.5 rounded-full bg-secondary-container text-on-secondary-container text-[10px] font-extrabold uppercase">Warga Belajar</span>
                                    </div>
                                    <span class="text-xs text-on-surface-variant font-medium">PKBM Karawang</span>
                                </div>
                            </a>
                        </div>

                        <!-- Desktop Navigation Links for Peserta -->
                        <nav class="hidden md:flex items-center gap-2">
                            <a href="{{ route('peserta.dashboard') }}"
                               class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('peserta.dashboard') ? 'bg-primary text-on-primary shadow-xs' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
                                <span class="material-symbols-outlined text-[18px]">home</span>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('peserta.pelatihan') }}"
                               class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('peserta.pelatihan') ? 'bg-primary text-on-primary shadow-xs' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
                                <span class="material-symbols-outlined text-[18px]">menu_book</span>
                                <span>Pelatihan Saya</span>
                            </a>
                            <a href="{{ route('peserta.cari-pelatihan') }}"
                               class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('peserta.cari-pelatihan') ? 'bg-primary text-on-primary shadow-xs' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
                                <span class="material-symbols-outlined text-[18px]">search</span>
                                <span>Cari Pelatihan</span>
                            </a>
                            <a href="{{ route('peserta.riwayat') }}"
                               class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('peserta.riwayat') ? 'bg-primary text-on-primary shadow-xs' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
                                <span class="material-symbols-outlined text-[18px]">history</span>
                                <span>Riwayat Hadir</span>
                            </a>
                        </nav>

                        <!-- User Profile Dropdown -->
                        <div class="flex items-center gap-3">
                            <div class="relative" x-data="{ profileOpen: false }">
                                <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false" class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-surface-container transition focus:outline-none">
                                    <div class="w-8 h-8 rounded-full bg-secondary text-on-secondary flex items-center justify-center text-xs font-bold shadow-xs">
                                        {{ strtoupper(substr(auth()->user()->nama, 0, 2)) }}
                                    </div>
                                    <div class="hidden sm:flex flex-col text-left">
                                        <span class="text-xs font-bold text-on-surface leading-tight truncate max-w-[120px]">{{ auth()->user()->nama }}</span>
                                        <span class="text-[10px] text-on-surface-variant">{{ auth()->user()->username }}</span>
                                    </div>
                                    <span class="material-symbols-outlined text-[18px] text-on-surface-variant">arrow_drop_down</span>
                                </button>

                                <div x-show="profileOpen" x-cloak
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     class="absolute right-0 mt-2 w-48 bg-surface-container-lowest rounded-xl shadow-xl border border-outline-variant/30 py-1.5 z-50">
                                    <div class="px-4 py-2 border-b border-outline-variant/20">
                                        <p class="text-[10px] text-on-surface-variant">Warga Belajar</p>
                                        <p class="text-xs font-bold text-on-surface truncate">{{ auth()->user()->nama }}</p>
                                    </div>
                                    <a href="{{ route('profile.index') }}" class="flex items-center gap-2 px-4 py-2 text-xs font-medium text-on-surface hover:bg-surface-container transition">
                                        <span class="material-symbols-outlined text-[18px] text-primary">person</span>
                                        Profil & Sandi
                                    </a>
                                    <div class="border-t border-outline-variant/20 my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-xs font-semibold text-error hover:bg-error-container/30 transition">
                                            <span class="material-symbols-outlined text-[18px] text-error">logout</span>
                                            Keluar (Logout)
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area for Peserta -->
            <main class="flex-1 pb-24 md:pb-12">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
                    <!-- Flash Notifications -->
                    @if(session('success'))
                        <div class="mb-5 flex items-center gap-3 p-4 rounded-xl bg-secondary-container/80 text-on-secondary-container border border-secondary shadow-xs">
                            <span class="material-symbols-outlined text-[22px] text-secondary">check_circle</span>
                            <div class="text-sm font-semibold">{{ session('success') }}</div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-5 flex items-center gap-3 p-4 rounded-xl bg-error-container text-on-error-container border border-error/30 shadow-xs">
                            <span class="material-symbols-outlined text-[22px] text-error">error</span>
                            <div class="text-sm font-semibold">{{ session('error') }}</div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-5 p-4 rounded-xl bg-error-container text-on-error-container border border-error/30 shadow-xs">
                            <div class="font-bold text-sm mb-1">Periksa kembali data Anda:</div>
                            <ul class="list-disc list-inside text-xs space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </main>

            <!-- Mobile Bottom Navigation Bar for Peserta (Enhanced with Center Floating Button) -->
            <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-surface-container-lowest/95 backdrop-blur-md border-t border-outline-variant/30 px-2 py-1.5 flex justify-around items-center shadow-lg">
                <a href="{{ route('peserta.dashboard') }}" class="flex flex-col items-center gap-0.5 text-[10px] font-semibold {{ request()->routeIs('peserta.dashboard') ? 'text-primary' : 'text-on-surface-variant' }}">
                    <span class="material-symbols-outlined text-[22px] {{ request()->routeIs('peserta.dashboard') ? 'fill' : '' }}">home</span>
                    <span>Beranda</span>
                </a>
                <a href="{{ route('peserta.pelatihan') }}" class="flex flex-col items-center gap-0.5 text-[10px] font-semibold {{ request()->routeIs('peserta.pelatihan') ? 'text-primary' : 'text-on-surface-variant' }}">
                    <span class="material-symbols-outlined text-[22px] {{ request()->routeIs('peserta.pelatihan') ? 'fill' : '' }}">menu_book</span>
                    <span>Pelatihan</span>
                </a>
                <a href="{{ route('peserta.cari-pelatihan') }}" class="flex flex-col items-center gap-0.5 text-[10px] font-semibold {{ request()->routeIs('peserta.cari-pelatihan') ? 'text-primary' : 'text-on-surface-variant' }}">
                    <span class="material-symbols-outlined text-[22px] {{ request()->routeIs('peserta.cari-pelatihan') ? 'fill' : '' }}">search</span>
                    <span>Cari</span>
                </a>
                <a href="{{ route('peserta.riwayat') }}" class="flex flex-col items-center gap-0.5 text-[10px] font-semibold {{ request()->routeIs('peserta.riwayat') ? 'text-primary' : 'text-on-surface-variant' }}">
                    <span class="material-symbols-outlined text-[22px] {{ request()->routeIs('peserta.riwayat') ? 'fill' : '' }}">history</span>
                    <span>Riwayat</span>
                </a>
            </nav>
        @endif
    @endauth

    <footer class="mt-auto border-t border-outline-variant/20 py-4 bg-surface-container-lowest hidden lg:block {{ auth()->check() && auth()->user()->isTutor() ? 'lg:pl-72' : '' }}">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs text-on-surface-variant">
            &copy; 2026 Pusat Kegiatan Belajar Masyarakat (PKBM) Karawang • Sistem Presensi Digital v1.0
        </div>
    </footer>

</body>
</html>
