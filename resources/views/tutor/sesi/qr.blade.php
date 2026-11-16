<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>QR Presensi Sesi — {{ $sesi->judul_sesi }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,600,700,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex flex-col font-sans text-slate-100 antialiased select-none"
      x-data="qrRotationHandler({{ $sesi->id }}, {{ $remainingSeconds }})"
      x-init="init()">

    <!-- Top Navigation Bar -->
    <header class="p-4 sm:p-6 flex items-center justify-between border-b border-white/10 bg-slate-900/50 backdrop-blur-md">
        <div class="flex items-center gap-3">
            <a href="{{ route('tutor.dashboard') }}" class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-white transition text-xs font-semibold flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali
            </a>
            <div>
                <span class="text-xs font-bold text-blue-400 uppercase tracking-wider">{{ $sesi->pelatihan->nama }}</span>
                <h1 class="text-sm sm:text-base font-bold text-white">{{ $sesi->judul_sesi }}</h1>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('tutor.sesi.live', $sesi) }}" target="_blank"
               class="px-3 py-1.5 bg-emerald-600/80 hover:bg-emerald-600 text-white font-semibold text-xs rounded-xl flex items-center gap-1.5 transition">
                <span class="w-2 h-2 rounded-full bg-emerald-300 animate-ping"></span>
                Monitoring Live &rarr;
            </a>

            <button type="button" @click="toggleFullscreen()"
                    class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-white transition text-xs font-semibold"
                    title="Layar Penuh (F11)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" /></svg>
            </button>
        </div>
    </header>

    <!-- Center Stage -->
    <main class="flex-1 flex flex-col items-center justify-center p-4 sm:p-8 text-center max-w-4xl mx-auto w-full">

        <!-- Info Pill -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-950/60 border border-blue-500/30 text-blue-300 text-xs font-medium mb-6">
            <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
            <span>Rotasi Otomatis Tiap 5 Menit &bull; Toleransi Jeda 15 Detik</span>
        </div>

        <!-- Big QR Code Frame -->
        <div class="relative bg-white p-6 sm:p-8 rounded-3xl shadow-2xl shadow-blue-500/10 border-4 border-white/20 transition-transform duration-300 transform hover:scale-[1.01]">
            
            <div id="qrContainer" class="w-64 h-64 sm:w-80 sm:h-80 flex items-center justify-center" x-html="qrSvg">
                {!! $qrSvg !!}
            </div>

            <!-- Subtle Logo in QR center (optional aesthetics) -->
            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-slate-400 text-xs">
                <span>Pusat Kegiatan Belajar</span>
                <span class="font-mono text-[11px] text-slate-500">{{ $sesi->lokasi }}</span>
            </div>
        </div>

        <!-- 6-Digit Manual Code Box (FR-06, FR-11) -->
        <div class="mt-6 bg-slate-900/80 border border-white/10 rounded-2xl px-6 py-4 max-w-md w-full shadow-lg">
            <span class="text-xs text-slate-400 uppercase font-semibold tracking-wider block mb-1">
                Kode Manual Sesi (Jika Kamera Bermasalah)
            </span>
            <div class="flex items-center justify-center gap-3">
                <span class="font-mono text-3xl sm:text-4xl font-black text-amber-400 tracking-[0.25em]" x-text="kodeManual">
                    {{ $sesi->kode_manual }}
                </span>
            </div>
        </div>

        <!-- Countdown Timer & Actions -->
        <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-4 text-xs text-slate-400">
            <div class="flex items-center gap-2 bg-white/5 border border-white/10 px-4 py-2 rounded-xl">
                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>Pergantian token dalam:</span>
                <span class="font-mono font-bold text-white text-sm" x-text="formattedRemaining">--:--</span>
            </div>

            <button type="button" @click="rotateNow()" :disabled="isLoading"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-500 disabled:opacity-50 text-white font-semibold rounded-xl shadow-md transition flex items-center gap-1.5 cursor-pointer">
                <svg :class="isLoading ? 'animate-spin' : ''" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                <span x-text="isLoading ? 'Memperbarui...' : 'Rotasi Sekarang'"></span>
            </button>
        </div>

    </main>

    <footer class="p-4 text-center text-xs text-slate-500 border-t border-white/5">
        Jam Server: <span class="font-mono text-slate-400" id="clockDisplay">{{ now()->format('H:i:s T') }}</span> &bull; Status: Aktif Menerima Presensi
    </footer>

    <script>
        function qrRotationHandler(sesiId, initialRemaining) {
            return {
                sesiId: sesiId,
                remaining: initialRemaining,
                kodeManual: '{{ $sesi->kode_manual }}',
                qrSvg: `{!! addslashes($qrSvg) !!}`,
                isLoading: false,
                interval: null,

                init() {
                    // Update clock
                    setInterval(() => {
                        const now = new Date();
                        const timeStr = now.toLocaleTimeString('id-ID');
                        const el = document.getElementById('clockDisplay');
                        if (el) el.innerText = timeStr;
                    }, 1000);

                    // Countdown interval
                    this.interval = setInterval(() => {
                        if (this.remaining > 0) {
                            this.remaining--;
                        } else {
                            this.checkOrRotate();
                        }
                    }, 1000);
                },

                get formattedRemaining() {
                    const m = Math.floor(this.remaining / 60);
                    const s = this.remaining % 60;
                    return `${m}:${s < 10 ? '0' : ''}${s}`;
                },

                async checkOrRotate() {
                    try {
                        const res = await fetch(`/tutor/sesi/${this.sesiId}/token-status`);
                        const data = await res.json();
                        this.qrSvg = data.qr_svg;
                        this.kodeManual = data.kode_manual;
                        this.remaining = data.remaining_seconds || 300;
                    } catch (e) {
                        console.error('Gagal memperbarui status token', e);
                    }
                },

                async rotateNow() {
                    this.isLoading = true;
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const res = await fetch(`/tutor/sesi/${this.sesiId}/rotate-token`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token
                            }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.qrSvg = data.qr_svg;
                            this.kodeManual = data.kode_manual;
                            this.remaining = data.remaining_seconds || 300;
                        }
                    } catch (e) {
                        alert('Gagal melakukan rotasi token.');
                    } finally {
                        this.isLoading = false;
                    }
                },

                toggleFullscreen() {
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen().catch(err => {});
                    } else {
                        document.exitFullscreen();
                    }
                }
            }
        }
    </script>
</body>
</html>
