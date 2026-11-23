<x-app-layout title="Pindai QR Presensi Sesi">
    <div class="max-w-xl mx-auto flex flex-col gap-5" x-data="qrScannerHandler()">

        <!-- Back Button -->
        <div>
            <a href="{{ route('peserta.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:underline">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                <span>Kembali ke Beranda</span>
            </a>
        </div>

        <!-- 1. Sesi & Informasi Konteks Banner (Stitch Component 1) -->
        <div class="w-full bg-surface-container-lowest rounded-2xl shadow-xs border border-outline-variant/30 p-5 flex flex-col gap-2">
            <div class="flex items-center justify-between">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container text-on-secondary-container text-xs font-bold">
                    <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                    Sesi Berlangsung
                </span>
                <span class="text-xs text-on-surface-variant font-medium">{{ now()->translatedFormat('d M Y') }}</span>
            </div>
            <div class="flex flex-col mt-0.5">
                <span class="text-xs font-bold text-primary">{{ $sesi->pelatihan->nama }}</span>
                <h2 class="text-lg font-bold text-on-surface tracking-tight">{{ $sesi->judul_sesi }}</h2>
                <div class="flex items-center gap-2 text-on-surface-variant text-xs mt-1">
                    <span class="material-symbols-outlined text-[16px] text-primary">location_on</span>
                    <span>{{ $sesi->lokasi }} ({{ substr((string)$sesi->jam_mulai, 0, 5) }} - {{ substr((string)$sesi->jam_selesai, 0, 5) }} WIB)</span>
                </div>
            </div>
        </div>

        @if($presensi)
            <!-- Already Attended Notice -->
            <div class="bg-surface-container-lowest border-2 border-secondary/40 rounded-2xl p-6 text-center shadow-xs">
                <div class="w-14 h-14 rounded-2xl bg-secondary-container text-secondary flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-outlined text-[32px]">verified</span>
                </div>
                <h2 class="text-lg font-bold text-on-surface">Presensi Anda Sudah Tercatat!</h2>
                <p class="text-xs text-on-surface-variant mt-1.5 leading-relaxed">
                    Status kehadiran: <strong class="text-secondary uppercase">{{ $presensi->status }}</strong> pada {{ $presensi->waktu_server->format('d/m/Y H:i:s') }} WIB (Metode: {{ $presensi->metode }}).
                </p>
                <div class="mt-5">
                    <a href="{{ route('peserta.dashboard') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-primary text-on-primary font-bold text-xs rounded-xl shadow-xs transition">
                        <span>Kembali ke Beranda</span>
                    </a>
                </div>
            </div>
        @elseif($isBeforeWindow)
            <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl p-6 text-center shadow-xs">
                <div class="w-14 h-14 rounded-2xl bg-surface-container text-on-surface-variant flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-outlined text-[32px]">schedule</span>
                </div>
                <h2 class="text-base font-bold text-on-surface">Presensi Belum Dibuka</h2>
                <p class="text-xs text-on-surface-variant mt-1.5 mb-5 leading-relaxed">
                    Jendela presensi baru dibuka 15 menit sebelum kelas dimulai (pukul <strong>{{ $sesi->window_buka->format('H:i') }} WIB</strong>).
                </p>
                <button type="button" onclick="window.location.reload()" class="px-5 py-2.5 bg-surface-container hover:bg-surface-container-high text-on-surface font-bold text-xs rounded-xl border border-outline-variant/30 transition">
                    Muat Ulang Halaman
                </button>
            </div>
        @elseif($isAfterWindow)
            <div class="bg-surface-container-lowest border border-error/30 rounded-2xl p-6 text-center shadow-xs">
                <div class="w-14 h-14 rounded-2xl bg-error-container text-error flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-outlined text-[32px]">timer_off</span>
                </div>
                <h2 class="text-base font-bold text-on-surface">Jam Sesi Telah Berakhir</h2>
                <p class="text-xs text-on-surface-variant mt-1.5 mb-5 leading-relaxed">
                    Waktu presensi untuk sesi ini telah ditutup (pukul {{ $sesi->waktu_selesai->format('H:i') }} WIB). Jika tutor baru saja membuka sesi kembali, silakan muat ulang.
                </p>
                <button type="button" onclick="window.location.reload()" class="px-5 py-2.5 bg-primary text-on-primary font-bold text-xs rounded-xl transition">
                    Periksa Ulang Sesi
                </button>
            </div>
        @else
            <!-- 2. Interactive Camera Viewfinder Panel (Stitch Component 2) -->
            <div class="w-full bg-inverse-surface rounded-2xl overflow-hidden shadow-xl flex flex-col relative text-surface">
                
                <!-- Viewport Box -->
                <div class="relative w-full h-[320px] bg-slate-950 flex flex-col items-center justify-center overflow-hidden">
                    <!-- Live Camera Reader Target -->
                    <div id="reader" class="w-full h-full object-cover"></div>

                    <!-- Overlay when camera is NOT running yet -->
                    <div x-show="!isScanning" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center bg-slate-950/90 z-20">
                        <!-- Reticle with neon corner markers -->
                        <div class="relative w-48 h-48 flex items-center justify-center mb-4">
                            <!-- Corners -->
                            <div class="absolute top-0 left-0 w-6 h-6 border-t-3 border-l-3 border-secondary-fixed rounded-tl-lg"></div>
                            <div class="absolute top-0 right-0 w-6 h-6 border-t-3 border-r-3 border-secondary-fixed rounded-tr-lg"></div>
                            <div class="absolute bottom-0 left-0 w-6 h-6 border-b-3 border-l-3 border-secondary-fixed rounded-bl-lg"></div>
                            <div class="absolute bottom-0 right-0 w-6 h-6 border-b-3 border-r-3 border-secondary-fixed rounded-br-lg"></div>

                            <span class="material-symbols-outlined text-[48px] text-surface-variant/40">qr_code_scanner</span>
                        </div>

                        <p class="text-xs text-surface-variant mb-4">Arahkan kamera ke layar QR proyektor untuk presensi instan.</p>

                        <div class="flex flex-col gap-2.5 w-full max-w-xs">
                            <button type="button" @click="startScanner()"
                                    class="w-full py-3 bg-primary hover:bg-primary-container text-on-primary font-bold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                                <span class="material-symbols-outlined text-[20px]">videocam</span>
                                <span>Buka Kamera Pemindai</span>
                            </button>

                            <label class="w-full py-2.5 bg-white/10 hover:bg-white/20 text-surface text-xs font-semibold rounded-xl border border-white/20 transition flex items-center justify-center gap-2 cursor-pointer">
                                <span class="material-symbols-outlined text-[18px]">photo_camera</span>
                                <span>Unggah Foto / Screenshot QR</span>
                                <input type="file" accept="image/*" @change="onFileSelected($event)" class="hidden">
                            </label>
                        </div>
                    </div>

                    <!-- Floating scanning guide overlay when active -->
                    <div x-show="isScanning" class="absolute bottom-3 left-0 right-0 px-4 text-center z-20 pointer-events-none">
                        <span class="text-[11px] font-semibold text-surface-variant bg-inverse-surface/85 backdrop-blur-md py-1 px-3 rounded-full inline-block">
                            Posisikan kode QR di dalam area pemindai
                        </span>
                    </div>

                    <!-- Close camera button when active -->
                    <div x-show="isScanning" class="absolute top-3 right-3 z-30">
                        <button type="button" @click="stopScanner()" class="px-3 py-1.5 bg-inverse-surface/80 backdrop-blur-md text-surface hover:bg-inverse-surface text-xs font-bold rounded-lg border border-white/20 transition">
                            Hentikan Kamera
                        </button>
                    </div>
                </div>

                <!-- Subtext Instructions -->
                <div class="p-4 bg-inverse-surface flex items-start gap-3 border-t border-white/10">
                    <span class="material-symbols-outlined text-[24px] text-secondary-fixed shrink-0">center_focus_strong</span>
                    <div class="flex flex-col">
                        <span class="text-sm font-bold text-surface">Arahkan Kamera ke Layar</span>
                        <span class="text-xs text-surface-container-high leading-relaxed mt-0.5">
                            Sistem akan otomatis mendeteksi kode QR yang ditampilkan tutor dan mencatat kehadiran Anda.
                        </span>
                    </div>
                </div>

                <div x-show="errorMessage" class="p-3 bg-error text-on-error text-xs font-semibold text-center" x-text="errorMessage"></div>
            </div>

            <!-- Hidden auto-submit form -->
            <form id="qrSubmitForm" method="POST" action="{{ route('peserta.presensi.submit') }}">
                @csrf
                <input type="hidden" name="sesi_id" value="{{ $sesi->id }}">
                <input type="hidden" name="token" id="qrTokenInput">
            </form>

            <!-- 3. Fallback: 6-Digit Manual Code Card (Stitch Component 3) -->
            <div class="w-full bg-surface-container rounded-2xl p-5 shadow-xs border border-outline-variant/30 flex flex-col gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-tertiary-fixed text-tertiary flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[22px]">pin</span>
                    </div>
                    <div class="flex flex-col">
                        <h3 class="text-sm font-bold text-on-surface">Kamera bermasalah atau sinyal lambat?</h3>
                        <p class="text-xs text-on-surface-variant">Gunakan 6 digit kode manual yang tertera di bawah QR pada layar Tutor.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('peserta.presensi.submit') }}" class="flex flex-col gap-3">
                    @csrf
                    <input type="hidden" name="sesi_id" value="{{ $sesi->id }}">

                    <div class="relative">
                        <input type="text" name="kode_manual" maxlength="6" required autocomplete="off"
                               class="w-full text-center tracking-[0.4em] font-mono font-extrabold text-2xl py-3 bg-surface-container-lowest border-2 border-outline-variant/40 rounded-xl uppercase focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none transition"
                               placeholder="XXXXXX">
                    </div>

                    <button type="submit"
                            class="w-full py-3 px-4 bg-primary hover:bg-primary-container text-on-primary font-bold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">send</span>
                        <span>Kirim Kode Manual</span>
                    </button>
                </form>
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

                async startScanner() {
                    this.errorMessage = '';
                    if (!window.Html5Qrcode) {
                        this.errorMessage = 'Library pemindai QR belum selesai dimuat. Silakan muat ulang halaman.';
                        return;
                    }

                    try {
                        if (!this.html5QrCode) {
                            this.html5QrCode = new window.Html5Qrcode("reader");
                        }

                        const config = { fps: 10, qrbox: { width: 220, height: 220 } };

                        await this.html5QrCode.start(
                            { facingMode: "environment" },
                            config,
                            (decodedText) => this.onScanSuccess(decodedText),
                            (error) => {}
                        );

                        this.isScanning = true;
                    } catch (err) {
                        try {
                            await this.html5QrCode.start(
                                { facingMode: "user" },
                                { fps: 10, qrbox: { width: 220, height: 220 } },
                                (decodedText) => this.onScanSuccess(decodedText),
                                (error) => {}
                            );
                            this.isScanning = true;
                        } catch (fallbackErr) {
                            this.isScanning = false;
                            this.errorMessage = 'Izin kamera ditolak atau tidak tersedia. Silakan gunakan tombol Unggah Foto QR atau masukkan Kode Manual 6-Digit di bawah.';
                        }
                    }
                },

                onScanSuccess(decodedText) {
                    if (this.html5QrCode && this.isScanning) {
                        this.html5QrCode.stop().catch(() => {}).finally(() => {
                            this.isScanning = false;
                            document.getElementById('qrTokenInput').value = decodedText;
                            document.getElementById('qrSubmitForm').submit();
                        });
                    } else {
                        document.getElementById('qrTokenInput').value = decodedText;
                        document.getElementById('qrSubmitForm').submit();
                    }
                },

                async onFileSelected(event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    this.errorMessage = '';
                    try {
                        if (!this.html5QrCode) {
                            this.html5QrCode = new window.Html5Qrcode("reader");
                        }
                        const decodedText = await this.html5QrCode.scanFile(file, true);
                        this.onScanSuccess(decodedText);
                    } catch (err) {
                        this.errorMessage = 'Kode QR tidak terbaca pada file gambar. Pastikan gambar jelas atau gunakan Kode Manual.';
                    }
                },

                stopScanner() {
                    if (this.html5QrCode && this.isScanning) {
                        this.html5QrCode.stop().then(() => {
                            this.isScanning = false;
                        }).catch(() => {});
                    }
                }
            }
        }
    </script>
</x-app-layout>
