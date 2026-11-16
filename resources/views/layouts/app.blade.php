<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Presensi Digital PKBM' }} — Sistem Presensi PKBM</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex flex-col min-h-screen font-sans text-slate-800 antialiased selection:bg-blue-600 selection:text-white">

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-40 bg-white border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                
                <!-- Logo & Brand -->
                <div class="flex items-center gap-3">
                    <a href="{{ auth()->check() ? (auth()->user()->isTutor() ? route('tutor.dashboard') : route('peserta.dashboard')) : route('login') }}" class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-700 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-blue-500/20 font-bold text-lg tracking-wider">
                            PK
                        </div>
                        <div class="flex flex-col">
                            <span class="text-base font-bold text-slate-900 leading-tight tracking-tight">Presensi PKBM</span>
                            <span class="text-xs text-slate-500 font-medium">Pusat Kegiatan Belajar</span>
                        </div>
                    </a>

                    @auth
                        <span class="hidden sm:inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ auth()->user()->isTutor() ? 'bg-indigo-100 text-indigo-800 border border-indigo-200' : 'bg-emerald-100 text-emerald-800 border border-emerald-200' }}">
                            {{ auth()->user()->isTutor() ? 'Tutor / Instruktur' : 'Warga Belajar' }}
                        </span>
                    @endauth
                </div>

                <!-- Navigation Links for Desktop -->
                @auth
                <nav class="hidden md:flex items-center gap-1.5">
                    @if(auth()->user()->isTutor())
                        <a href="{{ route('tutor.dashboard') }}" class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('tutor.dashboard') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('tutor.pelatihan.index') }}" class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('tutor.pelatihan.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            Pelatihan & Sesi
                        </a>
                        <a href="{{ route('tutor.users.index') }}" class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('tutor.users.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            Pengguna
                        </a>
                        <a href="{{ route('tutor.koreksi.index') }}" class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('tutor.koreksi.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            Koreksi Presensi
                        </a>
                        <a href="{{ route('tutor.laporan.index') }}" class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('tutor.laporan.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            Rekap Laporan
                        </a>
                    @else
                        <a href="{{ route('peserta.dashboard') }}" class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('peserta.dashboard') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('peserta.pelatihan') }}" class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('peserta.pelatihan') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            Pelatihan Saya
                        </a>
                        <a href="{{ route('peserta.cari-pelatihan') }}" class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('peserta.cari-pelatihan') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            Cari Pelatihan
                        </a>
                        <a href="{{ route('peserta.riwayat') }}" class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('peserta.riwayat') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            Riwayat Kehadiran
                        </a>
                    @endif
                </nav>

                <!-- User Profile & Action Dropdown -->
                <div class="flex items-center gap-3">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-slate-100 text-left transition focus:outline-hidden">
                            <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-700 font-bold flex items-center justify-center text-xs border border-slate-300">
                                {{ strtoupper(substr(auth()->user()->nama, 0, 2)) }}
                            </div>
                            <div class="hidden sm:block">
                                <p class="text-xs font-semibold text-slate-800 leading-tight truncate max-w-[120px]">{{ auth()->user()->nama }}</p>
                                <p class="text-[10px] text-slate-500">{{ auth()->user()->username }}</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" x-cloak
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-200 py-1.5 z-50">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-xs text-slate-500">Masuk sebagai</p>
                                <p class="text-xs font-bold text-slate-800 truncate">{{ auth()->user()->nama }}</p>
                            </div>
                            <a href="{{ route('profile.index') }}" class="flex items-center gap-2 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                Profil & Kata Sandi
                            </a>
                            @if(auth()->user()->isTutor())
                            <a href="{{ route('tutor.audit-logs') }}" class="flex items-center gap-2 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Log Audit Sistem
                            </a>
                            @endif
                            <div class="border-t border-slate-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-xs font-medium text-rose-600 hover:bg-rose-50 transition">
                                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                    Keluar (Logout)
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endauth

            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 pb-16 md:pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-5">

            <!-- Flash Notifications -->
            @if(session('success'))
                <div class="mb-5 flex items-center gap-3 p-4 text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200 shadow-xs" role="alert">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <div class="font-medium">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 flex items-center gap-3 p-4 text-sm text-rose-800 rounded-xl bg-rose-50 border border-rose-200 shadow-xs" role="alert">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <div class="font-medium">{{ session('error') }}</div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 p-4 text-sm text-rose-800 rounded-xl bg-rose-50 border border-rose-200 shadow-xs">
                    <div class="font-bold mb-1">Mohon periksa kembali formulir Anda:</div>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{ $slot }}
        </div>
    </main>

    <!-- Mobile Bottom Navigation Bar (Very friendly for Android phone users) -->
    @auth
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 px-2 py-2 flex justify-around items-center shadow-lg">
        @if(auth()->user()->isTutor())
            <a href="{{ route('tutor.dashboard') }}" class="flex flex-col items-center gap-1 text-[11px] font-medium {{ request()->routeIs('tutor.dashboard') ? 'text-blue-600 font-bold' : 'text-slate-500' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                Dashboard
            </a>
            <a href="{{ route('tutor.pelatihan.index') }}" class="flex flex-col items-center gap-1 text-[11px] font-medium {{ request()->routeIs('tutor.pelatihan.*') ? 'text-blue-600 font-bold' : 'text-slate-500' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                Pelatihan
            </a>
            <a href="{{ route('tutor.koreksi.index') }}" class="flex flex-col items-center gap-1 text-[11px] font-medium {{ request()->routeIs('tutor.koreksi.*') ? 'text-blue-600 font-bold' : 'text-slate-500' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                Koreksi
            </a>
            <a href="{{ route('tutor.laporan.index') }}" class="flex flex-col items-center gap-1 text-[11px] font-medium {{ request()->routeIs('tutor.laporan.*') ? 'text-blue-600 font-bold' : 'text-slate-500' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Laporan
            </a>
        @else
            <a href="{{ route('peserta.dashboard') }}" class="flex flex-col items-center gap-1 text-[11px] font-medium {{ request()->routeIs('peserta.dashboard') ? 'text-blue-600 font-bold' : 'text-slate-500' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                Dashboard
            </a>
            <a href="{{ route('peserta.pelatihan') }}" class="flex flex-col items-center gap-1 text-[11px] font-medium {{ request()->routeIs('peserta.pelatihan') ? 'text-blue-600 font-bold' : 'text-slate-500' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                Pelatihan
            </a>
            <a href="{{ route('peserta.cari-pelatihan') }}" class="flex flex-col items-center gap-1 text-[11px] font-medium {{ request()->routeIs('peserta.cari-pelatihan') ? 'text-blue-600 font-bold' : 'text-slate-500' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                Cari
            </a>
            <a href="{{ route('peserta.riwayat') }}" class="flex flex-col items-center gap-1 text-[11px] font-medium {{ request()->routeIs('peserta.riwayat') ? 'text-blue-600 font-bold' : 'text-slate-500' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                Riwayat
            </a>
        @endif
    </nav>
    @endauth

    <footer class="mt-auto border-t border-slate-200 py-4 bg-white hidden md:block">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs text-slate-500">
            &copy; 2026 Pusat Kegiatan Belajar Masyarakat (PKBM). Sistem Presensi Digital v1.0 Baseline.
        </div>
    </footer>

</body>
</html>
