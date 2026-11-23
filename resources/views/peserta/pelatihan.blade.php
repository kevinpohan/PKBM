<x-app-layout title="Pelatihan Yang Diikuti">
    <div class="flex flex-col gap-6">

        <!-- Header -->
        <div class="bg-surface-container-lowest p-5 sm:p-6 rounded-2xl shadow-xs border border-outline-variant/30 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-primary"></span>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-on-surface tracking-tight">Pelatihan Yang Diikuti</h1>
                </div>
                <p class="text-xs text-on-surface-variant mt-1">Daftar program pelatihan dan detail seluruh sesi pembelajaran Anda.</p>
            </div>
            <a href="{{ route('peserta.cari-pelatihan') }}"
               class="px-4 py-2.5 bg-primary hover:bg-primary-container text-on-primary font-bold text-xs rounded-xl shadow-xs transition self-start sm:self-auto flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">search</span>
                <span>Cari Pelatihan Baru</span>
            </a>
        </div>

        @if($pendaftarans->isEmpty())
            <div class="bg-surface-container-lowest rounded-2xl border border-dashed border-outline-variant/40 p-12 text-center">
                <div class="w-14 h-14 rounded-2xl bg-surface-container text-primary flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-outlined text-[32px]">menu_book</span>
                </div>
                <h3 class="text-base font-bold text-on-surface">Belum Ada Pelatihan Diikuti</h3>
                <p class="text-xs text-on-surface-variant mt-1 max-w-sm mx-auto">Anda belum mendaftar di pelatihan manapun atau permohonan Anda masih menunggu persetujuan tutor.</p>
                <a href="{{ route('peserta.cari-pelatihan') }}" class="inline-flex items-center gap-1.5 mt-5 px-5 py-2.5 bg-primary text-on-primary font-bold text-xs rounded-xl shadow-xs transition">
                    <span>Jelajahi Pelatihan Tersedia</span>
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>
        @else
            <div class="flex flex-col gap-6">
                @foreach($pendaftarans as $pendaftaran)
                    @php $pelatihan = $pendaftaran->pelatihan; @endphp
                    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-xs overflow-hidden">
                        
                        <!-- Training Header (Stitch Style) -->
                        <div class="p-5 sm:p-6 bg-surface-container-low/70 border-b border-outline-variant/20 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                    <span class="text-xs font-bold text-primary bg-primary-fixed px-2.5 py-0.5 rounded-full">
                                        Tutor: {{ $pelatihan->tutor->nama ?? 'Instruktur PKBM' }}
                                    </span>
                                    @if($pendaftaran->status === 'disetujui')
                                        <span class="text-xs font-bold text-secondary bg-secondary-container px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                                            <span>Aktif Terdaftar</span>
                                        </span>
                                    @elseif($pendaftaran->status === 'menunggu')
                                        <span class="text-xs font-bold text-amber-800 bg-amber-100 px-2.5 py-0.5 rounded-full">
                                            Menunggu Persetujuan
                                        </span>
                                    @else
                                        <span class="text-xs font-bold text-error bg-error-container px-2.5 py-0.5 rounded-full">
                                            Pendaftaran Ditolak
                                        </span>
                                    @endif
                                </div>
                                <h2 class="text-lg sm:text-xl font-extrabold text-on-surface tracking-tight">{{ $pelatihan->nama }}</h2>
                                @if($pelatihan->deskripsi)
                                    <p class="text-xs text-on-surface-variant mt-1 line-clamp-2 leading-relaxed">{{ $pelatihan->deskripsi }}</p>
                                @endif
                            </div>

                            <div class="text-xs text-on-surface-variant shrink-0 bg-surface-container px-3 py-1.5 rounded-xl self-start md:self-auto font-medium">
                                <strong>{{ $pelatihan->sesis->count() }}</strong> Sesi Pembelajaran
                            </div>
                        </div>

                        <!-- Sessions List -->
                        <div class="p-5 sm:p-6">
                            @if($pelatihan->sesis->isEmpty())
                                <p class="text-xs text-on-surface-variant italic">Belum ada sesi pembelajaran yang dijadwalkan pada pelatihan ini.</p>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left text-xs text-on-surface">
                                        <thead class="text-[11px] text-on-surface-variant uppercase bg-surface-container-low border-b border-outline-variant/20">
                                            <tr>
                                                <th class="py-2.5 px-3">Judul Sesi</th>
                                                <th class="py-2.5 px-3">Tanggal & Jam</th>
                                                <th class="py-2.5 px-3">Lokasi</th>
                                                <th class="py-2.5 px-3">Status Kehadiran</th>
                                                <th class="py-2.5 px-3 text-right">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-outline-variant/15">
                                            @foreach($pelatihan->sesis as $sesi)
                                                @php
                                                    $presensi = $sesi->presensis->first();
                                                    $now = now();
                                                    $isWithinWindow = $sesi->isWithinAttendanceWindow($now);
                                                    $isBefore = $sesi->isBeforeWindow($now);
                                                    $isAfter = $sesi->isAfterWindow($now);
                                                @endphp
                                                <tr class="hover:bg-surface-container-low/40 transition">
                                                    <td class="py-3 px-3 font-bold text-on-surface">
                                                        {{ $sesi->judul_sesi }}
                                                    </td>
                                                    <td class="py-3 px-3 whitespace-nowrap">
                                                        <div class="font-semibold text-on-surface">{{ $sesi->tanggal->format('d/m/Y') }}</div>
                                                        <div class="text-[11px] text-on-surface-variant">{{ substr((string)$sesi->jam_mulai, 0, 5) }} - {{ substr((string)$sesi->jam_selesai, 0, 5) }} WIB</div>
                                                    </td>
                                                    <td class="py-3 px-3 text-on-surface-variant">
                                                        {{ $sesi->lokasi }}
                                                    </td>
                                                    <td class="py-3 px-3 whitespace-nowrap">
                                                        @if($presensi)
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase
                                                                {{ $presensi->status === 'hadir' ? 'bg-secondary-container text-on-secondary-container' :
                                                                   ($presensi->status === 'terlambat' ? 'bg-amber-100 text-amber-900' :
                                                                   ($presensi->status === 'izin' ? 'bg-primary-fixed text-on-primary-fixed' :
                                                                   ($presensi->status === 'sakit' ? 'bg-purple-100 text-purple-900' : 'bg-error-container text-on-error-container'))) }}">
                                                                {{ $presensi->status }}
                                                            </span>
                                                        @elseif($isWithinWindow)
                                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-secondary-container text-on-secondary-container">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
                                                                Dibuka
                                                            </span>
                                                        @elseif($isBefore)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium text-on-surface-variant bg-surface-container">
                                                                Belum Dimulai
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-error-container text-on-error-container">
                                                                Alpa
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="py-3 px-3 text-right">
                                                        @if($presensi)
                                                            @if($presensi->status === 'alpa')
                                                                <span class="text-error font-bold text-[11px] flex items-center justify-end gap-1">
                                                                    <span class="material-symbols-outlined text-[16px]">cancel</span>
                                                                    <span>Alpa (Tidak Presensi)</span>
                                                                </span>
                                                            @else
                                                                <span class="text-secondary font-bold text-[11px] flex items-center justify-end gap-1">
                                                                    <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                                                    <span>Tercatat</span>
                                                                </span>
                                                            @endif
                                                        @elseif($isWithinWindow)
                                                            <a href="{{ route('peserta.presensi.pindai', $sesi) }}"
                                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary hover:bg-primary-container text-on-primary font-bold text-[11px] rounded-xl shadow-xs transition">
                                                                <span class="material-symbols-outlined text-[16px]">qr_code_scanner</span>
                                                                <span>Pindai QR</span>
                                                            </a>
                                                        @elseif($isBefore)
                                                            <span class="text-[11px] text-on-surface-variant">Buka jam {{ $sesi->window_buka->format('H:i') }}</span>
                                                        @else
                                                            <span class="text-error font-bold text-[11px] flex items-center justify-end gap-1">
                                                                <span class="material-symbols-outlined text-[16px]">cancel</span>
                                                                <span>Alpa (Tidak Presensi)</span>
                                                            </span>
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
