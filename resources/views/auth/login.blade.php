<!DOCTYPE html>
<html lang="id" class="h-full bg-surface">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Masuk — Presensi Digital PKBM Karawang</title>

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
<body class="bg-surface font-sans text-on-surface antialiased flex flex-col min-h-screen"
      x-data="{
          role: 'peserta',
          showPass: false,
          openHelp: false,
          fillDemo(u, p, r) {
              this.role = r;
              $refs.userInput.value = u;
              $refs.passInput.value = p;
          }
      }">

    <!-- Top Minimal Header Bar -->
    <header class="w-full bg-surface/90 backdrop-blur-xl border-b border-outline-variant/20 shadow-[0_1px_8px_rgba(0,0,0,0.03)] z-40">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-on-primary shadow-xs">
                    <span class="material-symbols-outlined text-[24px]">school</span>
                </div>
                <div class="flex flex-col">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-base text-primary leading-tight">Presensi PKBM</span>
                        <span class="px-2 py-0.5 rounded-full bg-secondary-container text-on-secondary-container text-[10px] font-extrabold uppercase">v1.0</span>
                    </div>
                    <span class="text-xs text-on-surface-variant">Pusat Kegiatan Belajar Masyarakat</span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" @click="openHelp = true" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-surface-container hover:bg-surface-container-high text-xs font-semibold text-on-surface transition">
                    <span class="material-symbols-outlined text-[18px] text-primary">help_outline</span>
                    <span class="hidden sm:inline">Bantuan Akun</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Container: Fully Flexible (Mobile Stacked, Desktop Side-by-Side or Centered) -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-md lg:max-w-4xl grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">

            <!-- Left / Top Showcase Card (Matching Stitch Brand Showcase) -->
            <div class="lg:col-span-5 relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary via-primary-container to-primary p-6 sm:p-8 shadow-xl text-on-primary flex flex-col justify-between">
                <div class="absolute -right-10 -bottom-10 w-44 h-44 rounded-full bg-white/10 pointer-events-none blur-xl"></div>
                <div class="absolute right-8 -top-8 w-32 h-32 rounded-full bg-secondary-fixed/20 pointer-events-none blur-lg"></div>

                <div class="relative z-10 flex flex-col gap-4">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center p-2 rounded-xl bg-white/15 text-secondary-fixed">
                            <span class="material-symbols-outlined fill text-[22px]">verified_user</span>
                        </span>
                        <span class="text-xs font-bold tracking-wider uppercase text-surface-container-high">Portal Resmi PKBM</span>
                    </div>

                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white leading-tight">
                            Sistem Presensi Belajar Digital
                        </h1>
                        <p class="text-xs sm:text-sm text-surface-container-high/90 mt-2 leading-relaxed">
                            Pencatatan presensi terverifikasi berbasis QR per sesi untuk Paket A, B, C dan Pelatihan Vokasi Keterampilan.
                        </p>
                    </div>

                    <!-- Highlight Badges -->
                    <div class="flex flex-col gap-2.5 pt-2">
                        <div class="flex items-center gap-2.5 text-xs text-white/90">
                            <span class="material-symbols-outlined text-secondary-fixed text-[18px]">qr_code_scanner</span>
                            <span>QR Dinamis Rotasi 5 Menit</span>
                        </div>
                        <div class="flex items-center gap-2.5 text-xs text-white/90">
                            <span class="material-symbols-outlined text-secondary-fixed text-[18px]">verified</span>
                            <span>Mencegah Titip Absen & Validasi Real-time</span>
                        </div>
                        <div class="flex items-center gap-2.5 text-xs text-white/90">
                            <span class="material-symbols-outlined text-secondary-fixed text-[18px]">file_download</span>
                            <span>Rekap Otomatis Excel & PDF Dapodik</span>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 pt-6 mt-6 border-t border-white/15 flex items-center justify-between text-xs text-surface-container-high">
                    <span>Standar Kemendikbudristek</span>
                    <span class="font-bold text-secondary-fixed">PKBM Terakreditasi</span>
                </div>
            </div>

            <!-- Right / Bottom Form Card -->
            <div class="lg:col-span-7 bg-surface-container-lowest rounded-2xl shadow-xl border border-outline-variant/30 p-6 sm:p-8 flex flex-col justify-between">
                <div>
                    <!-- Role Tab Selector (Matching Stitch) -->
                    <div class="p-1 rounded-xl bg-surface-container flex items-center gap-1 shadow-inner mb-6">
                        <button type="button"
                                @click="role = 'peserta'"
                                :class="role === 'peserta' ? 'bg-surface-container-lowest text-primary shadow-xs font-bold' : 'text-on-surface-variant font-medium hover:text-on-surface'"
                                class="flex-1 py-2.5 px-3 rounded-lg flex items-center justify-center gap-2 text-xs sm:text-sm transition-all duration-200">
                            <span class="material-symbols-outlined text-[18px]" :class="role === 'peserta' ? 'fill' : ''">school</span>
                            <span>Peserta Belajar</span>
                        </button>
                        <button type="button"
                                @click="role = 'tutor'"
                                :class="role === 'tutor' ? 'bg-surface-container-lowest text-primary shadow-xs font-bold' : 'text-on-surface-variant font-medium hover:text-on-surface'"
                                class="flex-1 py-2.5 px-3 rounded-lg flex items-center justify-center gap-2 text-xs sm:text-sm transition-all duration-200">
                            <span class="material-symbols-outlined text-[18px]" :class="role === 'tutor' ? 'fill' : ''">badge</span>
                            <span>Tutor / Instruktur</span>
                        </button>
                    </div>

                    <!-- Greeting Context -->
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-11 h-11 rounded-2xl bg-surface-container flex items-center justify-center text-primary flex-shrink-0">
                            <span class="material-symbols-outlined text-[24px]">fingerprint</span>
                        </div>
                        <div class="flex flex-col">
                            <h2 class="text-base sm:text-lg font-bold text-on-surface" x-text="role === 'peserta' ? 'Masuk sebagai Peserta Belajar' : 'Masuk sebagai Tutor / Instruktur'"></h2>
                            <p class="text-xs text-on-surface-variant" x-text="role === 'peserta' ? 'Gunakan Username atau NIK yang didaftarkan' : 'Gunakan Akun Pengajar untuk mengelola kelas'"></p>
                        </div>
                    </div>

                    <!-- Flash / Errors -->
                    @if(session('success'))
                        <div class="mb-5 flex items-center gap-2.5 p-3.5 rounded-xl bg-secondary-container/80 text-on-secondary-container border border-secondary text-xs font-semibold">
                            <span class="material-symbols-outlined text-[20px] text-secondary">check_circle</span>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-5 flex items-center gap-2.5 p-3.5 rounded-xl bg-error-container text-on-error-container border border-error/30 text-xs font-semibold">
                            <span class="material-symbols-outlined text-[20px] text-error">error</span>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <!-- Username / Identifier Field -->
                        <div class="flex flex-col gap-1.5">
                            <label for="username" class="text-xs font-bold text-on-surface flex items-center justify-between">
                                <span x-text="role === 'peserta' ? 'NISN / NIK / Nama Pengguna' : 'Username / NUPTK Tutor'"></span>
                                <span class="text-[11px] text-secondary font-semibold flex items-center gap-0.5">
                                    <span class="material-symbols-outlined text-[14px]">check_circle</span> Wajib
                                </span>
                            </label>
                            <div class="relative flex items-center">
                                <div class="absolute left-3.5 text-on-surface-variant pointer-events-none flex items-center">
                                    <span class="material-symbols-outlined text-[20px]">person</span>
                                </div>
                                <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus x-ref="userInput"
                                       :placeholder="role === 'peserta' ? 'Contoh: ahmad atau 0092837190' : 'Contoh: tutor1 atau budi.hartono'"
                                       class="w-full pl-11 pr-4 py-3 rounded-xl bg-surface-container-low text-on-surface text-sm placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-primary focus:bg-surface-container-lowest transition border border-transparent focus:border-primary">
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="flex flex-col gap-1.5">
                            <div class="flex items-center justify-between">
                                <label for="password" class="text-xs font-bold text-on-surface">Kata Sandi</label>
                                <button type="button" @click="openHelp = true" class="text-[11px] text-primary hover:underline font-semibold">
                                    Lupa kata sandi?
                                </button>
                            </div>
                            <div class="relative flex items-center">
                                <div class="absolute left-3.5 text-on-surface-variant pointer-events-none flex items-center">
                                    <span class="material-symbols-outlined text-[20px]">lock</span>
                                </div>
                                <input :type="showPass ? 'text' : 'password'" id="password" name="password" required x-ref="passInput"
                                       placeholder="Masukkan kata sandi"
                                       class="w-full pl-11 pr-11 py-3 rounded-xl bg-surface-container-low text-on-surface text-sm placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-primary focus:bg-surface-container-lowest transition border border-transparent focus:border-primary">
                                <button type="button" @click="showPass = !showPass" class="absolute right-3 text-on-surface-variant hover:text-on-surface focus:outline-none">
                                    <span class="material-symbols-outlined text-[20px]" x-text="showPass ? 'visibility_off' : 'visibility'"></span>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between text-xs pt-1">
                            <label class="flex items-center gap-2 cursor-pointer select-none text-on-surface-variant">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded text-primary focus:ring-primary border-outline-variant">
                                <span>Ingat login saya</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                                class="w-full py-3.5 px-4 rounded-xl bg-primary hover:bg-primary-container text-on-primary font-bold text-sm shadow-md transition-all active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer">
                            <span>Masuk Sekarang</span>
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </button>
                    </form>
                </div>

                <!-- One-Click Demo Accounts for Fast Testing & Evaluation -->
                <div class="mt-6 pt-4 border-t border-outline-variant/30 bg-surface-container-low/60 -mx-6 -mb-6 sm:-mx-8 sm:-mb-8 p-4 sm:p-5 rounded-b-2xl">
                    <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-2 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-secondary">flash_on</span>
                        <span>Akun Demo (Klik untuk Isi Otomatis):</span>
                    </p>
                    <div class="grid grid-cols-2 gap-2.5">
                        <button type="button" @click="fillDemo('tutor1', 'password123', 'tutor')"
                                class="p-2.5 rounded-xl bg-surface-container-lowest hover:bg-surface-container border border-outline-variant/30 text-left transition flex items-center justify-between group">
                            <div>
                                <span class="text-xs font-bold text-primary block leading-tight">Tutor / Pengelola</span>
                                <span class="text-[11px] text-on-surface-variant">User: tutor1</span>
                            </div>
                            <span class="material-symbols-outlined text-[18px] text-outline group-hover:text-primary transition">login</span>
                        </button>

                        <button type="button" @click="fillDemo('ahmad', 'password123', 'peserta')"
                                class="p-2.5 rounded-xl bg-surface-container-lowest hover:bg-surface-container border border-outline-variant/30 text-left transition flex items-center justify-between group">
                            <div>
                                <span class="text-xs font-bold text-secondary block leading-tight">Warga Belajar</span>
                                <span class="text-[11px] text-on-surface-variant">User: ahmad</span>
                            </div>
                            <span class="material-symbols-outlined text-[18px] text-outline group-hover:text-secondary transition">login</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Help / Forgot Password Modal -->
    <div x-show="openHelp" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-xs" @click="openHelp = false"></div>
        <div class="relative bg-surface-container-lowest rounded-2xl shadow-2xl max-w-sm w-full p-6 z-10 border border-outline-variant/30">
            <div class="w-12 h-12 rounded-2xl bg-primary-fixed text-primary flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-[28px]">contact_support</span>
            </div>
            <h3 class="text-lg font-bold text-on-surface mb-1">Bantuan Akun & Kata Sandi</h3>
            <p class="text-xs text-on-surface-variant leading-relaxed mb-4">
                Sesuai kebijakan PKBM, akun peserta dan tutor dikelola langsung oleh administrator/tutor lembaga. Jika lupa kata sandi atau akun belum aktif, silakan hubungi bagian administrasi PKBM.
            </p>
            <div class="p-3 rounded-xl bg-surface-container-low mb-5 text-xs text-on-surface">
                <p class="font-bold mb-1">Sekretariat PKBM Karawang:</p>
                <p class="text-on-surface-variant">Senin - Jumat: 08.00 - 16.00 WIB</p>
                <p class="text-primary font-semibold mt-1">WA: 0812-3456-7890</p>
            </div>
            <button type="button" @click="openHelp = false" class="w-full py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs">
                Tutup
            </button>
        </div>
    </div>

</body>
</html>
