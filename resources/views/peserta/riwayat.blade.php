<x-app-layout title="Riwayat Kehadiran">
    <div class="flex flex-col gap-6">

        <!-- Header -->
        <div class="bg-surface-container-lowest p-5 sm:p-6 rounded-2xl shadow-xs border border-outline-variant/30 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-primary"></span>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-on-surface tracking-tight">Riwayat & Rekap Kehadiran</h1>
                </div>
                <p class="text-xs text-on-surface-variant mt-1">Catatan akumulasi keaktifan presensi Anda di setiap program pelatihan.</p>
            </div>
            <a href="{{ route('peserta.dashboard') }}"
               class="px-3.5 py-2 bg-surface-container hover:bg-surface-container-high text-on-surface text-xs font-bold rounded-xl border border-outline-variant/30 transition self-start sm:self-auto flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">home</span>
                <span>Beranda</span>
            </a>
        </div>

        @if(empty($rekapPerPelatihan))
            <div class="bg-surface-container-lowest rounded-2xl border border-dashed border-outline-variant/40 p-12 text-center">
                <div class="w-12 h-12 rounded-2xl bg-surface-container text-on-surface-variant flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-outlined text-[28px]">history</span>
                </div>
                <p class="text-sm font-bold text-on-surface">Belum Ada Riwayat Pelatihan</p>
                <p class="text-xs text-on-surface-variant mt-1 mb-4">Anda belum aktif terdaftar pada kelas pembelajaran manapun.</p>
                <a href="{{ route('peserta.cari-pelatihan') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary text-on-primary font-bold text-xs rounded-xl shadow-xs transition">
                    <span>Cari Pelatihan Terbuka</span>
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>
        @else
            <div class="flex flex-col gap-6">
                @foreach($rekapPerPelatihan as $item)
                    @php
                        $pelatihan = $item['pelatihan'];
                        $persen = $item['persentase'];
                        $isHigh = $persen >= 80;
                        $isMid = $persen >= 50 && $persen < 80;
                    @endphp
                    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-xs overflow-hidden">
                        
                        <!-- Training Summary Header (Stitch Card) -->
                        <div class="p-5 sm:p-6 bg-surface-container-low/70 border-b border-outline-variant/20">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                                <div>
                                    <span class="text-xs font-bold text-primary bg-primary-fixed px-2.5 py-0.5 rounded-full inline-block mb-1.5">
                                        Tutor: {{ $pelatihan->tutor->nama ?? 'Instruktur PKBM' }}
                                    </span>
                                    <h2 class="text-lg sm:text-xl font-extrabold text-on-surface tracking-tight">{{ $pelatihan->nama }}</h2>
                                </div>

                                <div class="flex items-center gap-3 self-start md:self-auto">
                                    <div class="text-right">
                                        <div class="text-[11px] text-on-surface-variant font-medium">Tingkat Hadir</div>
                                        <div class="text-2xl font-black {{ $isHigh ? 'text-secondary' : ($isMid ? 'text-amber-600' : 'text-error') }}">
                                            {{ $persen }}%
                                        </div>
                                    </div>
                                    <div class="w-12 h-12 rounded-2xl bg-surface-container flex items-center justify-center font-extrabold text-xs text-on-surface border border-outline-variant/30">
                                        {{ $item['totalMasuk'] }}/{{ $item['totalSesi'] }}
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="w-full bg-surface-container-high rounded-full h-2.5 overflow-hidden mb-4">
                                <div class="h-2.5 rounded-full transition-all duration-500 {{ $isHigh ? 'bg-secondary' : ($isMid ? 'bg-amber-500' : 'bg-error') }}"
                                     style="width: {{ min(100, $persen) }}%"></div>
                            </div>

                            <!-- Quick Counts Bento Grid -->
                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 text-center text-xs">
                                <div class="bg-surface-container-lowest p-2.5 rounded-xl border border-outline-variant/30">
                                    <span class="text-[10px] text-on-surface-variant block uppercase font-bold">Hadir Tepat</span>
                                    <span class="text-base font-black text-secondary">{{ $item['hadir'] }}</span>
                                </div>
                                <div class="bg-surface-container-lowest p-2.5 rounded-xl border border-outline-variant/30">
                                    <span class="text-[10px] text-on-surface-variant block uppercase font-bold">Terlambat</span>
                                    <span class="text-base font-black text-amber-600">{{ $item['terlambat'] }}</span>
                                </div>
                                <div class="bg-surface-container-lowest p-2.5 rounded-xl border border-outline-variant/30">
                                    <span class="text-[10px] text-on-surface-variant block uppercase font-bold">Izin</span>
                                    <span class="text-base font-black text-primary">{{ $item['izin'] }}</span>
                                </div>
                                <div class="bg-surface-container-lowest p-2.5 rounded-xl border border-outline-variant/30">
                                    <span class="text-[10px] text-on-surface-variant block uppercase font-bold">Sakit</span>
                                    <span class="text-base font-black text-purple-700">{{ $item['sakit'] }}</span>
                                </div>
                                <div class="bg-surface-container-lowest p-2.5 rounded-xl border border-outline-variant/30 col-span-2 sm:col-span-1">
                                    <span class="text-[10px] text-on-surface-variant block uppercase font-bold">Alpa</span>
                                    <span class="text-base font-black text-error">{{ $item['alpa'] }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Session Detail Table -->
                        <div class="p-5 sm:p-6">
                            <h3 class="text-xs font-bold text-on-surface uppercase tracking-wider mb-3">Detail Presensi Seluruh Sesi</h3>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs text-on-surface">
                                    <thead class="text-[11px] text-on-surface-variant uppercase bg-surface-container-low border-b border-outline-variant/20">
                                        <tr>
                                            <th class="py-2.5 px-3">Tanggal</th>
                                            <th class="py-2.5 px-3">Judul Sesi</th>
                                            <th class="py-2.5 px-3">Jam & Lokasi</th>
                                            <th class="py-2.5 px-3">Status</th>
                                            <th class="py-2.5 px-3">Waktu Presensi</th>
                                            <th class="py-2.5 px-3">Metode</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-outline-variant/15">
                                        @forelse($pelatihan->sesis as $sesi)
                                            @php $presensi = $sesi->presensis->first(); @endphp
                                            <tr class="hover:bg-surface-container-low/40 transition">
                                                <td class="py-3 px-3 whitespace-nowrap font-medium text-on-surface">
                                                    {{ $sesi->tanggal->format('d/m/Y') }}
                                                </td>
                                                <td class="py-3 px-3 font-bold text-on-surface">
                                                    {{ $sesi->judul_sesi }}
                                                </td>
                                                <td class="py-3 px-3 text-on-surface-variant">
                                                    {{ substr((string)$sesi->jam_mulai, 0, 5) }} - {{ substr((string)$sesi->jam_selesai, 0, 5) }} WIB • {{ $sesi->lokasi }}
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
                                                    @elseif($sesi->isAfterWindow())
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-error-container text-on-error-container">
                                                            Alpa
                                                        </span>
                                                    @elseif($sesi->isWithinAttendanceWindow())
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-secondary-container text-on-secondary-container">
                                                            Dibuka
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold text-on-surface-variant bg-surface-container">
                                                            Belum Dimulai
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="py-3 px-3 font-mono text-on-surface">
                                                    {{ $presensi ? $presensi->waktu_server->format('H:i:s') : '-' }}
                                                </td>
                                                <td class="py-3 px-3 uppercase text-[10px] text-on-surface-variant font-semibold">
                                                    {{ $presensi ? $presensi->metode : '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="py-4 text-center text-on-surface-variant italic">
                                                    Belum ada sesi yang dibuat pada pelatihan ini.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </div>
</x-app-layout>
