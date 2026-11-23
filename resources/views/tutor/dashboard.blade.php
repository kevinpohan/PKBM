<x-app-layout title="Dashboard Utama Tutor">
    <div class="flex flex-col gap-6" x-data="{ activateModalOpen: false, activeJudul: '', activeJam: '', actionUrl: '' }">

        <!-- 1. Welcome Header & System Sync State (Stitch Section 1) -->
        <div class="relative overflow-hidden rounded-2xl bg-surface-container-lowest p-6 sm:p-8 shadow-xs border border-outline-variant/30 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-primary-fixed/30 blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col gap-2 max-w-2xl">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-3 py-1 rounded-full bg-secondary-container text-on-secondary-container text-xs font-bold flex items-center gap-1.5 shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                        Server Presensi Siap
                    </span>
                    <span class="text-outline text-xs">•</span>
                    <span class="text-xs text-on-surface-variant flex items-center gap-1 font-medium">
                        <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                        {{ now()->translatedFormat('l, d F Y') }}
                    </span>
                    <span class="text-outline text-xs">•</span>
                    <span class="text-xs text-on-surface-variant flex items-center gap-1 font-medium">
                        <span class="material-symbols-outlined text-[16px]">schedule</span>
                        WIB
                    </span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-extrabold text-on-surface tracking-tight mt-1">
                    Selamat Bertugas, {{ $user->nama }}
                </h1>
                <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                    Pusat Kegiatan Belajar Masyarakat (PKBM) Karawang • Kelola sesi pembelajaran, pantau presensi real-time, dan ekspor laporan resmi.
                </p>

                <!-- Quick Action Button Row -->
                <div class="flex flex-wrap items-center gap-2.5 pt-3">
                    <a href="{{ route('tutor.sesi.create') }}"
                       class="px-3.5 py-2 rounded-xl bg-primary hover:bg-primary-container text-on-primary font-bold text-xs shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        <span>+ Tambah Sesi</span>
                    </a>
                    <a href="{{ route('tutor.pelatihan.create') }}"
                       class="px-3.5 py-2 rounded-xl bg-surface-container hover:bg-surface-container-high text-on-surface font-semibold text-xs border border-outline-variant/30 transition flex items-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px] text-primary">add</span>
                        <span>Buat Pelatihan</span>
                    </a>
                    <a href="{{ route('tutor.users.create') }}"
                       class="px-3.5 py-2 rounded-xl bg-surface-container hover:bg-surface-container-high text-on-surface font-semibold text-xs border border-outline-variant/30 transition flex items-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px] text-secondary">person_add</span>
                        <span>Tambah Peserta</span>
                    </a>
                    <a href="{{ route('tutor.laporan.index') }}"
                       class="px-3.5 py-2 rounded-xl bg-surface-container hover:bg-surface-container-high text-on-surface font-semibold text-xs border border-outline-variant/30 transition flex items-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">download</span>
                        <span>Rekap Laporan</span>
                    </a>
                </div>
            </div>

            <div class="relative z-10 flex items-center gap-3 self-start lg:self-center">
                <div class="px-4 py-3 rounded-xl bg-surface-container-low flex items-center gap-3 border border-outline-variant/20">
                    <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[22px]">sync</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[11px] text-on-surface-variant">Sinkronisasi Sesi</span>
                        <span class="text-sm text-on-surface font-bold">100% Real-time</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Stat KPI Bento Cards Grid (Stitch Section 2) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Sesi Hari Ini -->
            <div class="bg-surface-container-lowest rounded-2xl p-5 shadow-xs border border-outline-variant/30 flex flex-col justify-between hover:shadow-sm transition-all">
                <div class="flex items-start justify-between">
                    <div class="flex flex-col">
                        <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Sesi Hari Ini</span>
                        <div class="flex items-baseline gap-1 mt-1">
                            <span class="text-3xl font-extrabold text-on-surface">{{ $sesiHariIni->count() }}</span>
                            <span class="text-xs text-on-surface-variant font-medium">Sesi</span>
                        </div>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-primary-fixed flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[24px]">calendar_today</span>
                    </div>
                </div>
                <div class="mt-4 pt-2 border-t border-outline-variant/10 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-secondary"></span>
                    <span class="text-xs text-secondary font-bold">Jadwal Kelas Aktif</span>
                </div>
            </div>

            <!-- Card 2: Warga Belajar Aktif -->
            <div class="bg-surface-container-lowest rounded-2xl p-5 shadow-xs border border-outline-variant/30 flex flex-col justify-between hover:shadow-sm transition-all">
                <div class="flex items-start justify-between">
                    <div class="flex flex-col">
                        <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Warga Belajar</span>
                        <div class="flex items-baseline gap-1 mt-1">
                            <span class="text-3xl font-extrabold text-on-surface">{{ $totalPeserta }}</span>
                            <span class="text-xs text-on-surface-variant font-medium">Peserta</span>
                        </div>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-secondary-container flex items-center justify-center text-on-secondary-container">
                        <span class="material-symbols-outlined text-[24px]">group</span>
                    </div>
                </div>
                <div class="mt-4 pt-2 border-t border-outline-variant/10 flex items-center justify-between">
                    <span class="text-xs text-on-surface-variant">Akun peserta terdaftar</span>
                    <span class="text-[11px] bg-surface-container px-2 py-0.5 rounded font-semibold text-on-surface">Aktif</span>
                </div>
            </div>

            <!-- Card 3: Pelatihan Aktif -->
            <div class="bg-surface-container-lowest rounded-2xl p-5 shadow-xs border border-outline-variant/30 flex flex-col justify-between hover:shadow-sm transition-all">
                <div class="flex items-start justify-between">
                    <div class="flex flex-col">
                        <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Pelatihan Aktif</span>
                        <div class="flex items-baseline gap-1 mt-1">
                            <span class="text-3xl font-extrabold text-primary">{{ $totalPelatihanAktif }}</span>
                            <span class="text-xs text-on-surface-variant font-medium">Program</span>
                        </div>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-primary-fixed-dim flex items-center justify-center text-on-primary-fixed">
                        <span class="material-symbols-outlined text-[24px]">menu_book</span>
                    </div>
                </div>
                <div class="mt-4 pt-2 border-t border-outline-variant/10 flex items-center justify-between">
                    <span class="text-xs text-on-surface-variant">Paket A, B, C & Vokasi</span>
                    <span class="text-[11px] text-primary font-bold">Berjalan</span>
                </div>
            </div>

            <!-- Card 4: Tutor / Pengajar -->
            <div class="bg-surface-container-lowest rounded-2xl p-5 shadow-xs border border-outline-variant/30 flex flex-col justify-between hover:shadow-sm transition-all">
                <div class="flex items-start justify-between">
                    <div class="flex flex-col">
                        <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Tutor Pengajar</span>
                        <div class="flex items-baseline gap-1 mt-1">
                            <span class="text-3xl font-extrabold text-on-surface">{{ $totalTutor }}</span>
                            <span class="text-xs text-on-surface-variant font-medium">Instruktur</span>
                        </div>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-surface-container-high flex items-center justify-center text-on-surface">
                        <span class="material-symbols-outlined text-[24px]">badge</span>
                    </div>
                </div>
                <div class="mt-4 pt-2 border-t border-outline-variant/10 flex items-center justify-between">
                    <span class="text-xs text-on-surface-variant">Standar Pengampu</span>
                    <span class="text-[11px] text-secondary font-bold">Terverifikasi</span>
                </div>
            </div>
        </div>

        <!-- 3. Sesi Hari Ini: Active Session Focus -->
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-xs p-5 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-outline-variant/20 mb-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-primary"></span>
                        <h2 class="text-base sm:text-lg font-bold text-on-surface">Sesi Pembelajaran Hari Ini</h2>
                    </div>
                    <p class="text-xs text-on-surface-variant mt-0.5">Tampilkan layar QR projector atau pantau kehadiran langsung dari ponsel/laptop.</p>
                </div>
                <span class="text-xs font-semibold text-on-surface-variant bg-surface-container px-3 py-1.5 rounded-xl self-start sm:self-auto">
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>
            </div>

            @if($sesiHariIni->isEmpty())
                <div class="text-center py-12 px-4 rounded-xl bg-surface-container-low/50 border border-dashed border-outline-variant/40">
                    <div class="w-12 h-12 rounded-2xl bg-surface-container flex items-center justify-center mx-auto text-on-surface-variant mb-3">
                        <span class="material-symbols-outlined text-[28px]">event_busy</span>
                    </div>
                    <p class="text-sm font-bold text-on-surface">Tidak ada sesi kelas yang dijadwalkan hari ini</p>
                    <p class="text-xs text-on-surface-variant mt-1 mb-4">Anda dapat membuat sesi baru dan langsung membukanya untuk presensi kelas.</p>
                    <a href="{{ route('tutor.sesi.create') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary-container text-on-primary font-bold text-xs rounded-xl shadow-xs transition">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        <span>+ Buat Sesi Baru Sekarang</span>
                    </a>
                </div>
            @else
                <div class="flex flex-col gap-3.5">
                    @foreach($sesiHariIni as $sesi)
                        @php
                            $now = now();
                            $isWithin = $sesi->isWithinAttendanceWindow($now);
                            $totalAttended = $sesi->presensis->whereIn('status', ['hadir', 'terlambat'])->count();
                        @endphp
                        <div class="p-4 sm:p-5 rounded-2xl border border-outline-variant/30 bg-surface-container-low/60 flex flex-col lg:flex-row lg:items-center justify-between gap-4 hover:border-primary/40 transition">
                            <div class="space-y-1.5">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-xs font-bold text-primary">{{ $sesi->pelatihan->nama }}</span>
                                    <span class="text-outline text-xs">•</span>
                                    <span class="text-xs text-on-surface-variant flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px] text-primary">location_on</span>
                                        {{ $sesi->lokasi }}
                                    </span>
                                    @if($isWithin)
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-secondary-container text-on-secondary-container flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
                                            Presensi Terbuka
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-surface-container text-on-surface-variant">
                                            Di Luar Jam Sesi
                                        </span>
                                    @endif
                                </div>
                                <h3 class="text-base font-bold text-on-surface">{{ $sesi->judul_sesi }}</h3>
                                <div class="flex items-center gap-3 text-xs text-on-surface-variant pt-0.5 flex-wrap">
                                    <span class="flex items-center gap-1 font-medium">
                                        <span class="material-symbols-outlined text-[16px]">schedule</span>
                                        {{ substr((string)$sesi->jam_mulai, 0, 5) }} – {{ substr((string)$sesi->jam_selesai, 0, 5) }} WIB
                                    </span>
                                    <span class="text-outline">•</span>
                                    <span class="font-bold text-secondary flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                        {{ $totalAttended }} Peserta Hadir
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-2 shrink-0">
                                @if(!$isWithin)
                                    <button type="button"
                                            @click="activeJudul = '{{ addslashes($sesi->judul_sesi) }}'; activeJam = '{{ substr((string)$sesi->jam_mulai, 0, 5) }} – {{ substr((string)$sesi->jam_selesai, 0, 5) }} WIB'; actionUrl = '{{ route('tutor.sesi.activate-now', $sesi) }}'; activateModalOpen = true;"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer"
                                            title="Sesuaikan jam sesi ke waktu sekarang agar peserta bisa langsung scan QR">
                                        <span>⚡ Buka Sekarang</span>
                                    </button>
                                @endif

                                <a href="{{ route('tutor.sesi.qr', $sesi) }}" target="_blank"
                                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-primary hover:bg-primary-container text-on-primary font-bold text-xs rounded-xl shadow-xs transition">
                                    <span class="material-symbols-outlined text-[18px]">qr_code_2</span>
                                    <span>Layar QR Sesi</span>
                                </a>

                                <a href="{{ route('tutor.sesi.live', $sesi) }}"
                                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-secondary hover:bg-secondary/90 text-on-secondary font-bold text-xs rounded-xl shadow-xs transition">
                                    <span class="material-symbols-outlined text-[18px]">fact_check</span>
                                    <span>Monitoring Live</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- 4. Two Columns: Pending Registrations & Audit Logs -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Pendaftaran Menunggu Persetujuan -->
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-xs p-5 sm:p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-outline-variant/20 mb-3">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[20px]">how_to_reg</span>
                            <h3 class="text-sm font-bold text-on-surface">Permohonan Masuk Kelas</h3>
                        </div>
                        <span class="text-xs font-bold text-primary bg-primary-fixed px-2.5 py-0.5 rounded-full">
                            {{ $pendingPendaftarans->count() }} Pending
                        </span>
                    </div>

                    @if($pendingPendaftarans->isEmpty())
                        <div class="py-8 text-center text-xs text-on-surface-variant italic">
                            Tidak ada permohonan pendaftaran yang menunggu persetujuan.
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($pendingPendaftarans as $p)
                                <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/20 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                                    <div>
                                        <div class="font-bold text-on-surface">{{ $p->user->nama }}</div>
                                        <div class="text-[11px] text-on-surface-variant">{{ $p->pelatihan->nama }} • {{ $p->tanggal_daftar->diffForHumans() }}</div>
                                    </div>
                                    <div class="flex items-center gap-1.5 shrink-0 self-end sm:self-auto">
                                        <form method="POST" action="{{ route('tutor.pelatihan.pendaftaran.approve', $p) }}">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-secondary hover:bg-secondary/90 text-on-secondary font-bold rounded-lg transition text-[11px] cursor-pointer">
                                                Setujui
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('tutor.pelatihan.pendaftaran.reject', $p) }}">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-error-container hover:bg-error/20 text-error font-bold rounded-lg border border-error/30 transition text-[11px] cursor-pointer">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="pt-3 mt-3 border-t border-outline-variant/20 flex justify-end">
                    <a href="{{ route('tutor.pelatihan.index') }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                        <span>Kelola Kelas & Kuota</span>
                        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                    </a>
                </div>
            </div>

            <!-- Recent Audit Logs -->
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-xs p-5 sm:p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-outline-variant/20 mb-3">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary text-[20px]">history_edu</span>
                            <h3 class="text-sm font-bold text-on-surface">Koreksi Presensi Terakhir</h3>
                        </div>
                        <a href="{{ route('tutor.audit-logs') }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-0.5">
                            Lihat Semua &rarr;
                        </a>
                    </div>

                    @if($recentAuditLogs->isEmpty())
                        <div class="py-8 text-center text-xs text-on-surface-variant italic">
                            Belum ada riwayat koreksi presensi.
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($recentAuditLogs as $log)
                                <div class="p-3 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs">
                                    <div class="flex justify-between items-start gap-2 mb-1.5">
                                        <div>
                                            <span class="font-bold text-on-surface">{{ $log->presensi->user->nama ?? 'Peserta' }}</span>
                                            <span class="text-outline">•</span>
                                            <span class="text-on-surface-variant">{{ $log->presensi->sesi->judul_sesi ?? 'Sesi' }}</span>
                                        </div>
                                        <span class="text-[10px] text-on-surface-variant font-mono">{{ $log->created_at->format('d/m H:i') }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 mb-1.5">
                                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-surface-container text-on-surface uppercase">{{ $log->status_sebelum ?? 'BELUM' }}</span>
                                        <span class="text-outline">&rarr;</span>
                                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-secondary-container text-on-secondary-container uppercase">{{ $log->status_sesudah }}</span>
                                        <span class="text-[11px] text-on-surface-variant ml-1">oleh {{ $log->pelaku->nama ?? 'Tutor' }}</span>
                                    </div>
                                    <p class="text-[11px] text-on-surface-variant bg-surface-container-lowest p-2 rounded-lg border border-outline-variant/20 italic">
                                        "{{ $log->alasan }}"
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="pt-3 mt-3 border-t border-outline-variant/20 flex justify-end">
                    <a href="{{ route('tutor.audit-logs') }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                        <span>Log Audit Lengkap</span>
                        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                    </a>
                </div>
            </div>

        </div>

        <!-- Modal Pop Up Konfirmasi Buka Sesi (Tanpa Browser Alert) -->
        <div x-show="activateModalOpen" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/75 backdrop-blur-xs"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="bg-surface-container-lowest text-on-surface rounded-3xl p-6 sm:p-7 max-w-md w-full shadow-2xl border border-outline-variant/30 flex flex-col gap-4 text-left"
                 @click.outside="activateModalOpen = false"
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
                    <div class="font-bold text-on-surface" x-text="activeJudul"></div>
                    <div class="text-[11px] text-on-surface-variant">
                        Jadwal terdaftar: <span class="font-semibold text-on-surface" x-text="activeJam"></span>.
                    </div>
                    <div class="text-[11px] text-on-surface-variant leading-relaxed">
                        Jam sesi akan disesuaikan otomatis mulai sekarang hingga <strong>2 jam ke depan</strong>. Peserta yang sebelumnya terlewat jam presensi dapat langsung memindai QR dan mencatatkan kehadirannya.
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-1">
                    <button type="button" @click="activateModalOpen = false"
                            class="px-4 py-2.5 rounded-xl bg-surface-container hover:bg-surface-container-high text-on-surface text-xs font-bold transition cursor-pointer">
                        Batal
                    </button>
                    <form :action="actionUrl" method="POST">
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

    </div>
</x-app-layout>
