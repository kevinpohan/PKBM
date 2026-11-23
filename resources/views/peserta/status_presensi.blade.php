<x-app-layout title="Konfirmasi Presensi">
    <div class="max-w-lg mx-auto py-4 sm:py-8">

        @if($result['success'] ?? false)
            <!-- ============================================== -->
            <!-- SUKSES: KONFIRMASI PRESENSI BERHASIL (STITCH) -->
            <!-- ============================================== -->
            <div class="bg-surface-container-lowest rounded-3xl border-2 border-secondary/40 shadow-xl overflow-hidden text-center">
                
                <!-- Hero Header -->
                <div class="bg-gradient-to-b from-secondary to-secondary/90 p-8 text-on-secondary relative overflow-hidden">
                    <div class="absolute -right-8 -bottom-8 w-36 h-36 rounded-full bg-white/10 blur-lg pointer-events-none"></div>

                    <div class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/30 shadow-xs backdrop-blur-sm">
                        <span class="material-symbols-outlined fill text-[48px] text-on-secondary">verified</span>
                    </div>

                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/20 border border-white/30 mb-2">
                        Presensi Berhasil Tercatat
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Status: {{ strtoupper($result['status']) }}</h1>
                    @if($result['status'] === 'terlambat')
                        <p class="text-secondary-fixed text-xs mt-1">(Tercatat melewati batas toleransi keterlambatan)</p>
                    @endif
                </div>

                <!-- Receipt Detail Context -->
                <div class="p-6 sm:p-8 space-y-5 text-left">
                    <div class="bg-surface-container-low rounded-2xl p-4 sm:p-5 border border-outline-variant/30 space-y-3 text-xs">
                        <div class="flex justify-between items-center py-1 border-b border-outline-variant/20">
                            <span class="text-on-surface-variant font-medium">Nama Peserta</span>
                            <span class="font-bold text-on-surface">{{ $result['nama_peserta'] }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1 border-b border-outline-variant/20">
                            <span class="text-on-surface-variant font-medium">Sesi Pembelajaran</span>
                            <span class="font-bold text-primary text-right max-w-[220px] truncate">{{ $result['nama_sesi'] }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1 border-b border-outline-variant/20">
                            <span class="text-on-surface-variant font-medium">Pelatihan</span>
                            <span class="font-semibold text-on-surface text-right max-w-[220px] truncate">{{ $result['nama_pelatihan'] }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1 border-b border-outline-variant/20">
                            <span class="text-on-surface-variant font-medium">Waktu Server</span>
                            <span class="font-mono font-bold text-secondary text-sm">{{ $result['jam_server'] }} WIB</span>
                        </div>
                        <div class="flex justify-between items-center py-1 border-b border-outline-variant/20">
                            <span class="text-on-surface-variant font-medium">Tanggal</span>
                            <span class="font-semibold text-on-surface">{{ $result['tanggal'] }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="text-on-surface-variant font-medium">Metode Verifikasi</span>
                            <span class="font-bold text-primary bg-primary-fixed px-2 py-0.5 rounded-full uppercase text-[10px]">{{ $result['metode'] }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2.5 pt-2">
                        <a href="{{ route('peserta.dashboard') }}"
                           class="w-full py-3.5 px-4 bg-primary hover:bg-primary-container text-on-primary font-bold text-xs rounded-xl text-center shadow-md transition flex items-center justify-center gap-2">
                            <span>Kembali ke Beranda</span>
                            <span class="material-symbols-outlined text-[18px]">home</span>
                        </a>
                        <a href="{{ route('peserta.riwayat') }}"
                           class="w-full py-3 px-4 bg-surface-container hover:bg-surface-container-high text-on-surface font-bold text-xs rounded-xl text-center border border-outline-variant/30 transition flex items-center justify-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px]">history</span>
                            <span>Lihat Riwayat Kehadiran</span>
                        </a>
                    </div>
                </div>

            </div>
        @else
            <!-- ============================================== -->
            <!-- GAGAL: PRESENSI DITOLAK & SOLUSI (STITCH)      -->
            <!-- ============================================== -->
            <div class="bg-surface-container-lowest rounded-3xl border-2 border-error/30 shadow-xl overflow-hidden text-center">
                
                <!-- Hero Header -->
                <div class="bg-gradient-to-b from-error to-error/90 p-8 text-on-error relative overflow-hidden">
                    <div class="absolute -right-8 -bottom-8 w-36 h-36 rounded-full bg-white/10 blur-lg pointer-events-none"></div>

                    <div class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/30 shadow-xs backdrop-blur-sm">
                        <span class="material-symbols-outlined text-[48px] text-on-error">cancel</span>
                    </div>

                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/20 border border-white/30 mb-2">
                        Presensi Ditolak
                    </span>
                    <h1 class="text-2xl font-extrabold tracking-tight">Presensi Tidak Berhasil</h1>
                </div>

                <div class="p-6 sm:p-8 space-y-5 text-left">
                    <!-- Failure Reason Box -->
                    <div class="bg-error-container text-on-error-container rounded-2xl p-4 border border-error/30 flex items-start gap-3">
                        <span class="material-symbols-outlined text-[24px] text-error shrink-0 mt-0.5">error</span>
                        <div class="flex flex-col">
                            <span class="text-[11px] font-bold text-error uppercase tracking-wider">Penyebab:</span>
                            <p class="text-xs font-bold leading-relaxed mt-0.5">
                                {{ $result['alasan'] ?? 'Terjadi kendala validasi saat memproses presensi.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Practical Solutions Guidance -->
                    <div class="bg-surface-container-low rounded-2xl p-4 border border-outline-variant/30 text-xs text-on-surface">
                        <span class="font-bold text-on-surface block mb-2 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-primary text-[18px]">lightbulb</span>
                            <span>Langkah Solusi:</span>
                        </span>
                        <ul class="list-disc list-inside space-y-1 text-on-surface-variant text-[11px] leading-relaxed">
                            <li>Jika QR kedaluwarsa, tunggu 5 menit atau minta tutor menyegarkan kode QR.</li>
                            <li>Pastikan Anda terdaftar aktif di pelatihan ini.</li>
                            <li>Gunakan alternatif input kode manual 6-digit jika kamera bermasalah.</li>
                            <li>Hubungi tutor pengampu jika memerlukan koreksi kehadiran manual.</li>
                        </ul>
                    </div>

                    <div class="flex flex-col gap-2.5 pt-2">
                        <a href="{{ url()->previous() }}"
                           class="w-full py-3.5 px-4 bg-primary hover:bg-primary-container text-on-primary font-bold text-xs rounded-xl text-center shadow-md transition flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">refresh</span>
                            <span>Coba Pindai Ulang</span>
                        </a>
                        <a href="{{ route('peserta.dashboard') }}"
                           class="w-full py-3 px-4 bg-surface-container hover:bg-surface-container-high text-on-surface font-bold text-xs rounded-xl text-center border border-outline-variant/30 transition">
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>

            </div>
        @endif

    </div>
</x-app-layout>
