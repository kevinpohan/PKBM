<x-app-layout title="Pelatihan Yang Diikuti">
    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-200">
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Pelatihan Yang Diikuti</h1>
                <p class="text-xs text-slate-500">Daftar kelas dan jadwal seluruh sesi pembelajaran Anda.</p>
            </div>
            <a href="{{ route('peserta.cari-pelatihan') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                Cari Pelatihan Lain
            </a>
        </div>

        @if($pendaftarans->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">Belum Ada Pelatihan Diikuti</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Anda belum mendaftar di pelatihan manapun atau pendaftaran Anda masih menunggu verifikasi.</p>
                <a href="{{ route('peserta.cari-pelatihan') }}" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-xl">
                    Jelajahi Pelatihan Tersedia &rarr;
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($pendaftarans as $pendaftaran)
                    @php $pelatihan = $pendaftaran->pelatihan; @endphp
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                        
                        <!-- Training Header -->
                        <div class="p-5 sm:p-6 bg-slate-50 border-b border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-2 py-0.5 rounded-md">
                                        Tutor: {{ $pelatihan->tutor->nama ?? 'Tutor PKBM' }}
                                    </span>
                                    @if($pendaftaran->status === 'disetujui')
                                        <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md">
                                            Aktif Terdaftar
                                        </span>
                                    @elseif($pendaftaran->status === 'menunggu')
                                        <span class="text-[11px] font-semibold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-md">
                                            Menunggu Persetujuan Tutor
                                        </span>
                                    @else
                                        <span class="text-[11px] font-semibold text-rose-700 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-md">
                                            Pendaftaran Ditolak
                                        </span>
                                    @endif
                                </div>
                                <h2 class="text-lg font-bold text-slate-900">{{ $pelatihan->nama }}</h2>
                                @if($pelatihan->deskripsi)
                                    <p class="text-xs text-slate-600 mt-1 line-clamp-2">{{ $pelatihan->deskripsi }}</p>
                                @endif
                            </div>

                            <div class="text-xs text-slate-500 shrink-0">
                                <strong>{{ $pelatihan->sesis->count() }}</strong> Sesi Pembelajaran
                            </div>
                        </div>

                        <!-- Sessions List (FR-04) -->
                        <div class="p-5 sm:p-6">
                            @if($pelatihan->sesis->isEmpty())
                                <p class="text-xs text-slate-400 italic">Belum ada sesi yang dijadwalkan untuk pelatihan ini.</p>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left text-xs text-slate-600">
                                        <thead class="text-[11px] text-slate-400 uppercase bg-slate-50/70 border-b border-slate-100">
                                            <tr>
                                                <th class="py-2.5 px-3">Judul Sesi</th>
                                                <th class="py-2.5 px-3">Tanggal & Jam</th>
                                                <th class="py-2.5 px-3">Lokasi</th>
                                                <th class="py-2.5 px-3">Status Kehadiran</th>
                                                <th class="py-2.5 px-3 text-right">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @foreach($pelatihan->sesis as $sesi)
                                                @php
                                                    $presensi = $sesi->presensis->first();
                                                    $now = now();
                                                    $isWithinWindow = $sesi->isWithinAttendanceWindow($now);
                                                    $isBefore = $sesi->isBeforeWindow($now);
                                                    $isAfter = $sesi->isAfterWindow($now);
                                                @endphp
                                                <tr class="hover:bg-slate-50/50">
                                                    <td class="py-3 px-3 font-semibold text-slate-900">
                                                        {{ $sesi->judul_sesi }}
                                                    </td>
                                                    <td class="py-3 px-3 whitespace-nowrap">
                                                        <div class="font-medium text-slate-800">{{ $sesi->tanggal->format('d/m/Y') }}</div>
                                                        <div class="text-[11px] text-slate-500">{{ substr((string)$sesi->jam_mulai, 0, 5) }} - {{ substr((string)$sesi->jam_selesai, 0, 5) }} WIB</div>
                                                    </td>
                                                    <td class="py-3 px-3">
                                                        {{ $sesi->lokasi }}
                                                    </td>
                                                    <td class="py-3 px-3 whitespace-nowrap">
                                                        @if($presensi)
                                                            @php
                                                                $colors = [
                                                                    'hadir' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                                                    'terlambat' => 'bg-amber-100 text-amber-800 border-amber-200',
                                                                    'izin' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                                    'sakit' => 'bg-purple-100 text-purple-800 border-purple-200',
                                                                    'alpa' => 'bg-rose-100 text-rose-800 border-rose-200',
                                                                ];
                                                                $c = $colors[$presensi->status] ?? 'bg-slate-100 text-slate-800';
                                                            @endphp
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $c }}">
                                                                {{ strtoupper($presensi->status) }}
                                                            </span>
                                                            <span class="block text-[10px] text-slate-400 mt-0.5">{{ $presensi->waktu_server->format('H:i') }} ({{ $presensi->metode }})</span>
                                                        @else
                                                            @if($isBefore)
                                                                <span class="text-[11px] text-slate-400">Belum dimulai</span>
                                                            @elseif($isWithinWindow)
                                                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-600 animate-pulse">
                                                                    &bull; Presensi Terbuka
                                                                </span>
                                                            @else
                                                                <span class="text-[11px] text-rose-500 font-medium">Sesi Berakhir</span>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td class="py-3 px-3 text-right whitespace-nowrap">
                                                        @if(!$presensi && $pendaftaran->status === 'disetujui' && $isWithinWindow)
                                                            <a href="{{ route('peserta.presensi.pindai', $sesi) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-lg shadow-xs transition">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                                                                Pindai QR
                                                            </a>
                                                        @elseif($presensi)
                                                            <span class="text-[11px] text-emerald-600 font-semibold">Tercatat</span>
                                                        @else
                                                            <span class="text-[11px] text-slate-400">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </div>
</x-app-layout>
