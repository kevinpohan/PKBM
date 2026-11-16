<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Presensi Digital PKBM</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center font-sans antialiased bg-radial from-slate-100 to-slate-200 text-slate-800 p-4">

    <div class="w-full max-w-md">
        
        <!-- App Logo & Title -->
        <div class="text-center mb-8">
            <div class="inline-flex w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-700 via-indigo-600 to-blue-500 items-center justify-center text-white shadow-xl shadow-blue-500/25 font-black text-2xl tracking-wider mb-3">
                PK
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Presensi Digital PKBM</h1>
            <p class="text-sm text-slate-500 mt-1">Pusat Kegiatan Belajar Masyarakat</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-200/50 p-6 sm:p-8">
            <h2 class="text-lg font-bold text-slate-900 mb-1">Masuk ke Sistem</h2>
            <p class="text-xs text-slate-500 mb-6">Gunakan username dan kata sandi yang telah didaftarkan oleh Tutor.</p>

            @if(session('success'))
                <div class="mb-5 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-800 font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="username" class="block text-xs font-semibold text-slate-700 mb-1.5">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </div>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus
                               class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                               placeholder="Contoh: tutor1 atau ahmad">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">Kata Sandi</label>
                    <div class="relative" x-data="{ show: false }">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <input :type="show ? 'text' : 'password'" id="password" name="password" required
                               class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                               placeholder="••••••••">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-slate-600">Ingat sesi saya</span>
                    </label>
                    <span class="text-slate-400 text-[11px]">Bantuan? Hubungi Tutor</span>
                </div>

                <button type="submit"
                        class="w-full mt-2 py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold text-sm rounded-xl shadow-md shadow-blue-500/20 transition duration-150 transform active:scale-[0.99] cursor-pointer">
                    Masuk Sekarang
                </button>
            </form>

            <!-- Quick Demo Credentials Box for Evaluation -->
            <div class="mt-6 pt-5 border-t border-slate-100 bg-slate-50/80 -mx-6 -mb-6 p-4 rounded-b-2xl text-[11px] text-slate-500">
                <p class="font-bold text-slate-700 mb-1">Akun Percobaan (Demo Seed):</p>
                <div class="grid grid-cols-2 gap-2">
                    <div class="p-2 bg-white rounded-lg border border-slate-200">
                        <span class="font-bold text-indigo-700 block">Tutor / Pengelola:</span>
                        Username: <code class="font-bold text-slate-800">tutor1</code><br>
                        Password: <code class="text-slate-800">password123</code>
                    </div>
                    <div class="p-2 bg-white rounded-lg border border-slate-200">
                        <span class="font-bold text-emerald-700 block">Warga Belajar:</span>
                        Username: <code class="font-bold text-slate-800">ahmad</code><br>
                        Password: <code class="text-slate-800">password123</code>
                    </div>
                </div>
            </div>

        </div>

        <p class="text-center text-xs text-slate-400 mt-6">
            Sistem Presensi Berbasis QR Dinamis &bull; Ringan & Ramah Kuota
        </p>

    </div>

</body>
</html>
