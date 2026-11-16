<x-app-layout title="Dashboard Tutor & Pengelola">
    <div class="space-y-6">

        <!-- Top Header Banner -->
        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-900 rounded-2xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
            <div class="relative z-10 max-w-2xl">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-blue-200 border border-white/15 mb-3">
                    Panel Tutor & Operasional PKBM
                </span>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Selamat Datang, {{ $user->nama }}</h1>
                <p class="text-slate-300 text-sm mt-1.5 leading-relaxed">
                    Kelola sesi pembelajaran, tampilkan QR presensi kelas secara real-time, pantau kehadiran warga belajar, dan buat rekapitulasi laporan resmi.
                </p>
                <div class="flex flex-wrap items-center gap-3 mt-5">
                    <a href="{{ route('tutor.pelatihan.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition cursor-pointer">
                        + Buat Pelatihan Baru
                    </a>
                    <a href="{{ route('tutor.users.create') }}" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white font-semibold text-xs rounded-xl border border-white/20 backdrop-blur-md transition cursor-pointer">
                        + Tambah / Impor Peserta
                    </a>
                    <a href="{{ route('tutor.laporan.index') }}" class="px-4 py-2 bg-white text-slate-900 font-semibold text-xs rounded-xl shadow-xs hover:bg-slate-100 transition cursor-pointer">
                        Rekapitulasi Laporan
                    </a>
                </div>
            </div>
            <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-blue-500/10 rounded-full pointer-events-none"></div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
                <div class="flex items-center justify-between text-slate-500 mb-2">
                    <span class="text-xs font-semibold">Warga Belajar Aktif</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900">{{ $totalPeserta }}</div>
                <span class="text-[11px] text-slate-400">Akun peserta aktif</span>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
                <div class="flex items-center justify-between text-slate-500 mb-2">
                    <span class="text-xs font-semibold">Tutor / Pengajar</span>
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-indigo-600">{{ $totalTutor }}</div>
                <span class="text-[11px] text-slate-400">Instruktur terdaftar</span>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
                <div class="flex items-center justify-between text-slate-500 mb-2">
                    <span class="text-xs font-semibold">Pelatihan Aktif</span>
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-blue-600">{{ $totalPelatihanAktif }}</div>
                <span class="text-[11px] text-slate-400">Program berjalan</span>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
                <div class="flex items-center justify-between text-slate-500 mb-2">
                    <span class="text-xs font-semibold">Sesi Hari Ini</span>
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-amber-600">{{ $sesiHariIni->count() }}</div>
                <span class="text-[11px] text-slate-400">Jadwal kelas aktif</span>
            </div>

        </div>

        <!-- Sesi Aktif Hari Ini (FR-09, FR-11, FR-12) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Sesi Pembelajaran Hari Ini</h2>
                    <p class="text-xs text-slate-500">Tampilkan QR layar penuh atau pantau kehadiran langsung di kelas.</p>
                </div>
                <span class="text-xs font-semibold text-slate-600 bg-slate-100 px-3 py-1 rounded-lg">
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>
            </div>

            @if($sesiHariIni->isEmpty())
                <div class="text-center py-10 px-4">
                    <p class="text-sm font-semibold text-slate-700">Tidak ada sesi yang dijadwalkan hari ini</p>
                    <p class="text-xs text-slate-400 mt-1">Anda dapat menambahkan sesi baru melalui menu Pelatihan.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($sesiHariIni as $sesi)
                        @php
                            $now = now();
                            $isWithin = $sesi->isWithinAttendanceWindow($now);
                            $totalAttended = $sesi->presensis->whereIn('status', ['hadir', 'terlambat'])->count();
                        @endphp
                        <div class="p-4 sm:p-5 rounded-2xl border border-slate-200 bg-slate-50/60 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-indigo-700">{{ $sesi->pelatihan->nama }}</span>
                                    <span class="text-slate-300">&bull;</span>
                                    <span class="text-xs text-slate-500">{{ $sesi->lokasi }}</span>
                                </div>
                                <h3 class="text-base font-bold text-slate-900">{{ $sesi->judul_sesi }}</h3>
                                <div class="flex items-center gap-3 text-xs text-slate-600 pt-0.5">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        {{ substr((string)$sesi->jam_mulai, 0, 5) }} – {{ substr((string)$sesi->jam_selesai, 0, 5) }} WIB
                                    </span>
                                    <span class="text-slate-300">&bull;</span>
                                    <span class="font-medium text-emerald-700">
                                        {{ $totalAttended }} Peserta Hadir
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                                <a href="{{ route('tutor.sesi.qr', $sesi) }}" target="_blank"
                                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                                    Layar QR Fullscreen
                                </a>

                                <a href="{{ route('tutor.sesi.live', $sesi) }}"
                                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-xs transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    Monitoring Live
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- 2 Columns: Pending Approvals & Recent Audit Logs -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Pendaftaran Menunggu Persetujuan -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                    <h3 class="text-sm font-bold text-slate-900">Permohonan Masuk Kelas (Pending)</h3>
                    <span class="text-xs font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200">
                        {{ $pendingPendaftarans->count() }} Menunggu
                    </span>
                </div>

                @if($pendingPendaftarans->isEmpty())
                    <p class="text-xs text-slate-400 py-6 text-center italic">Tidak ada pendaftaran yang menunggu persetujuan.</p>
                @else
                    <div class="space-y-3">
                        @foreach($pendingPendaftarans as $p)
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-3 text-xs">
                                <div>
                                    <div class="font-bold text-slate-900">{{ $p->user->nama }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $p->pelatihan->nama }} &bull; {{ $p->tanggal_daftar->diffForHumans() }}</div>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <form method="POST" action="{{ route('tutor.pelatihan.pendaftaran.approve', $p) }}">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition text-[11px] cursor-pointer">
                                            Setujui
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('tutor.pelatihan.pendaftaran.reject', $p) }}">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold rounded-lg border border-rose-200 transition text-[11px] cursor-pointer">
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Recent Audit Logs (FR-13) -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                    <h3 class="text-sm font-bold text-slate-900">Aktivitas Koreksi Presensi Terakhir</h3>
                    <a href="{{ route('tutor.audit-logs') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                        Lihat Semua &rarr;
                    </a>
                </div>

                @if($recentAuditLogs->isEmpty())
                    <p class="text-xs text-slate-400 py-6 text-center italic">Belum ada riwayat koreksi presensi.</p>
                @else
                    <div class="space-y-3">
                        @foreach($recentAuditLogs as $log)
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                                <div class="flex justify-between items-start gap-2 mb-1">
                                    <div>
                                        <span class="font-bold text-slate-900">{{ $log->presensi->user->nama ?? 'Peserta' }}</span>
                                        <span class="text-slate-400">&bull;</span>
                                        <span class="text-slate-500">{{ $log->presensi->sesi->judul_sesi ?? 'Sesi' }}</span>
                                    </div>
                                    <span class="text-[10px] text-slate-400 font-mono">{{ $log->created_at->format('d/m H:i') }}</span>
                                </div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-sm bg-slate-200 text-slate-700">{{ strtoupper($log->status_sebelum ?? 'BELUM') }}</span>
                                    <span class="text-slate-400">&rarr;</span>
                                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-sm bg-emerald-100 text-emerald-800">{{ strtoupper($log->status_sesudah) }}</span>
                                    <span class="text-[10px] text-slate-500">oleh {{ $log->pelaku->nama ?? 'Tutor' }}</span>
                                </div>
                                <p class="text-[11px] text-slate-600 bg-white p-2 rounded-lg border border-slate-200/80 italic">
                                    "{{ $log->alasan }}"
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>
</x-app-layout>
