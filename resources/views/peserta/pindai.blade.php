<x-app-layout title="Pindai QR Presensi Sesi">
    <div class="max-w-xl mx-auto space-y-6" x-data="qrScannerHandler()">

        <!-- Breadcrumb / Back -->
        <a href="{{ route('peserta.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
            &larr; Kembali ke Dashboard
        </a>

        <!-- Session Header Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5">
            <div class="flex items-center justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-blue-700 bg-blue-50 px-2.5 py-0.5 rounded-lg border border-blue-200">
                    {{ $sesi->pelatihan->nama }}
                </span>
                <span class="text-xs text-slate-500 font-medium">
                    {{ $sesi->lokasi }}
                </span>
            </div>
            <h1 class="text-lg font-bold text-slate-900">{{ $sesi->judul_sesi }}</h1>
            <div class="flex items-center gap-2 mt-2 text-xs text-slate-600">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ substr((string)$sesi->jam_mulai, 0, 5) }} - {{ substr((string)$sesi->jam_selesai, 0, 5) }} WIB</span>
                <span class="text-slate-300">&bull;</span>
                <span class="text-slate-500">Batas Terlambat: +{{ $sesi->batas_terlambat_menit }} mnt</span>
            </div>
        </div>

        @if($presensi)
            <!-- Already Attended Notice -->
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6 text-center shadow-xs">
                <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                </div>
                <h2 class="text-base font-bold text-emerald-900">Kehadiran Sudah Tercatat</h2>
                <p class="text-xs text-emerald-700 mt-1">
                    Status: <strong>{{ strtoupper($presensi->status) }}</strong> pada {{ $presensi->waktu_server->format('d/m/Y H:i:s') }} (Metode: {{ $presensi->metode }}).
                </p>
                <a href="{{ route('peserta.dashboard') }}" class="inline-block mt-4 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-xs transition">
                    Kembali ke Dashboard
                </a>
            </div>
        @elseif($isBeforeWindow)
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-center shadow-xs">
                <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <h2 class="text-base font-bold text-amber-900">Presensi Belum Dibuka</h2>
                <p class="text-xs text-amber-700 mt-1">
                    Jendela presensi baru dibuka 15 menit sebelum kelas dimulai (pukul <strong>{{ $sesi->window_buka->format('H:i') }} WIB</strong>). Silakan tunggu arahan Tutor.
                </p>
            </div>
        @elseif($isAfterWindow)
            <div class="bg-rose-50 border border-rose-200 rounded-2xl p-6 text-center shadow-xs">
                <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </div>
                <h2 class="text-base font-bold text-rose-900">Presensi Sesi Ditutup</h2>
                <p class="text-xs text-rose-700 mt-1">
                    Jam selesai sesi telah berakhir (pukul {{ $sesi->waktu_selesai->format('H:i') }} WIB). Peserta yang belum presensi tercatat otomatis sebagai Alpa. Hubungi Tutor jika ada kendala.
                </p>
            </div>
        @else
            <!-- Scanner Card (FR-06) -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                        <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Pemindai Kamera QR</h2>
                    </div>
                    <span class="text-[11px] text-slate-500">Arahkan ke layar tutor</span>
                </div>

                <div class="p-4 sm:p-6 text-center">
                    
                    <!-- Viewport Kamera -->
                    <div class="relative bg-slate-900 rounded-2xl overflow-hidden aspect-square max-w-xs mx-auto flex items-center justify-center shadow-inner">
                        <div id="reader" class="w-full h-full"></div>

                        <div x-show="!isScanning" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-white text-center bg-slate-900/90 z-10">
                            <div class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </div>
                            <p class="text-xs font-medium text-slate-300 mb-4">Kamera belum aktif. Klik tombol di bawah untuk mengaktifkan kamera ponsel.</p>
                            <button type="button" @click="startScanner()" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-md transition cursor-pointer">
                                Buka Kamera Belakang
                            </button>
                        </div>
                    </div>

                    <div x-show="isScanning" class="mt-4 flex items-center justify-center gap-2">
                        <button type="button" @click="stopScanner()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition">
                            Hentikan Kamera
                        </button>
                    </div>

                    <p x-show="errorMessage" x-text="errorMessage" class="text-xs text-rose-600 font-medium mt-3"></p>
                </div>

                <!-- Hidden form for QR scan auto-submission -->
                <form id="qrSubmitForm" method="POST" action="{{ route('peserta.presensi.submit') }}">
                    @csrf
                    <input type="hidden" name="sesi_id" value="{{ $sesi->id }}">
                    <input type="hidden" name="token" id="qrTokenInput">
                </form>

                <!-- Alternative: 6-Digit Manual Code Input (FR-06) -->
                <div class="p-5 sm:p-6 bg-slate-50 border-t border-slate-200">
                    <div class="text-center mb-3">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Alternatif: Kode Manual Sesi</span>
                        <p class="text-[11px] text-slate-500">Gunakan jika kamera bermasalah atau buram.</p>
                    </div>

                    <form method="POST" action="{{ route('peserta.presensi.submit') }}" class="max-w-xs mx-auto space-y-3">
                        @csrf
                        <input type="hidden" name="sesi_id" value="{{ $sesi->id }}">

                        <div class="relative">
                            <input type="text" name="kode_manual" maxlength="6" required
                                   class="w-full text-center tracking-[0.4em] font-mono font-black text-xl py-2.5 bg-white border-2 border-slate-300 rounded-xl uppercase focus:border-blue-600 focus:ring-0 focus:outline-hidden"
                                   placeholder="XXXXXX">
                        </div>

                        <button type="submit"
                                class="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-xl shadow-xs transition cursor-pointer">
                            Kirim Kode Presensi
                        </button>
                    </form>
                </div>

            </div>
        @endif

    </div>

    <!-- Scanner Script using html5-qrcode -->
    <script>
        function qrScannerHandler() {
            return {
                isScanning: false,
                html5QrCode: null,
                errorMessage: '',

                startScanner() {
                    this.errorMessage = '';
                    if (!window.Html5Qrcode) {
                        this.errorMessage = 'Library pemindai QR belum selesai dimuat. Silakan muat ulang halaman.';
                        return;
                    }

                    this.html5QrCode = new window.Html5Qrcode("reader");
                    const config = { fps: 10, qrbox: { width: 250, height: 250 } };

                    // Prefer back camera (environment)
                    this.html5QrCode.start(
                        { facingMode: "environment" },
                        config,
                        (decodedText) => {
                            // On Success Scan: Stop scanner and submit form
                            this.html5QrCode.stop().then(() => {
                                this.isScanning = false;
                                document.getElementById('qrTokenInput').value = decodedText;
                                document.getElementById('qrSubmitForm').submit();
                            }).catch(() => {
                                document.getElementById('qrTokenInput').value = decodedText;
                                document.getElementById('qrSubmitForm').submit();
                            });
                        },
                        (error) => {
                            // Scan error (frame didn't contain QR, ignore for continuous scan)
                        }
                    ).then(() => {
                        this.isScanning = true;
                    }).catch((err) => {
                        this.isScanning = false;
                        this.errorMessage = 'Izin kamera ditolak atau perangkat tidak memiliki kamera yang kompatibel. Gunakan Kode Manual 6-Digit di bawah.';
                    });
                },

                stopScanner() {
                    if (this.html5QrCode && this.isScanning) {
                        this.html5QrCode.stop().then(() => {
                            this.isScanning = false;
                        });
                    }
                }
            }
        }
    </script>
</x-app-layout>
