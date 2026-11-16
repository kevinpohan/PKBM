<x-app-layout title="Riwayat Kehadiran">
    <div class="space-y-6">

        <div class="flex items-center justify-between pb-4 border-b border-slate-200">
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Riwayat & Persentase Kehadiran</h1>
                <p class="text-xs text-slate-500">Pantau catatan keaktifan dan akumulasi kehadiran Anda per program pelatihan.</p>
            </div>
        </div>

        @if(empty($rekapPerPelatihan))
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                <p class="text-sm font-semibold text-slate-700">Belum Ada Riwayat Pelatihan</p>
                <p class="text-xs text-slate-400 mt-1">Anda belum aktif terdaftar pada pelatihan manapun.</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($rekapPerPelatihan as $item)
                    @php
                        $pelatihan = $item['pelatihan'];
                        $persen = $item['persentase'];
                        $colorClass = $persen >= 80 ? 'text-emerald-600 bg-emerald-500' : ($persen >= 50 ? 'text-amber-600 bg-amber-500' : 'text-rose-600 bg-rose-500');
                    @endphp
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                        
                        <!-- Training Summary Header -->
                        <div class="p-5 sm:p-6 bg-slate-50 border-b border-slate-200">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                                <div>
                                    <span class="text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-2.5 py-0.5 rounded-md mb-1 inline-block">
                                        Tutor: {{ $pelatihan->tutor->nama ?? 'Tutor PKBM' }}
                                    </span>
                                    <h2 class="text-lg font-bold text-slate-900">{{ $pelatihan->nama }}</h2>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="text-right">
                                        <div class="text-xs text-slate-500 font-medium">Tingkat Kehadiran</div>
                                        <div class="text-2xl font-black {{ explode(' ', $colorClass)[0] }}">
                                            {{ $persen }}%
                                        </div>
                                    </div>
                                    <div class="w-12 h-12 rounded-full bg-slate-200 flex items-center justify-center font-bold text-xs text-slate-700">
                                        {{ $item['totalMasuk'] }}/{{ $item['totalSesi'] }}
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden mb-4">
                                <div class="h-2.5 rounded-full {{ explode(' ', $colorClass)[1] }} transition-all duration-500" style="width: {{ min(100, $persen) }}%"></div>
                            </div>

                            <!-- Quick Counts -->
                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 text-center text-xs">
                                <div class="bg-white p-2 rounded-xl border border-slate-200">
                                    <span class="text-[10px] text-slate-400 block uppercase font-bold">Hadir</span>
                                    <span class="text-base font-bold text-emerald-600">{{ $item['hadir'] }}</span>
                                </div>
                                <div class="bg-white p-2 rounded-xl border border-slate-200">
                                    <span class="text-[10px] text-slate-400 block uppercase font-bold">Terlambat</span>
                                    <span class="text-base font-bold text-amber-600">{{ $item['terlambat'] }}</span>
                                </div>
                                <div class="bg-white p-2 rounded-xl border border-slate-200">
                                    <span class="text-[10px] text-slate-400 block uppercase font-bold">Izin</span>
                                    <span class="text-base font-bold text-blue-600">{{ $item['izin'] }}</span>
                                </div>
                                <div class="bg-white p-2 rounded-xl border border-slate-200">
                                    <span class="text-[10px] text-slate-400 block uppercase font-bold">Sakit</span>
                                    <span class="text-base font-bold text-purple-600">{{ $item['sakit'] }}</span>
                                </div>
                                <div class="bg-white p-2 rounded-xl border border-slate-200 col-span-2 sm:col-span-1">
                                    <span class="text-[10px] text-slate-400 block uppercase font-bold">Alpa</span>
                                    <span class="text-base font-bold text-rose-600">{{ $item['alpa'] }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Session Detail Table -->
                        <div class="p-5 sm:p-6">
                            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Detail Kehadiran Seluruh Sesi</h3>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs text-slate-600">
                                    <thead class="text-[11px] text-slate-400 uppercase bg-slate-50 border-b border-slate-100">
                                        <tr>
                                            <th class="py-2 px-3">Tanggal</th>
                                            <th class="py-2 px-3">Judul Sesi</th>
                                            <th class="py-2 px-3">Jam & Lokasi</th>
                                            <th class="py-2 px-3">Status</th>
                                            <th class="py-2 px-3">Waktu Presensi</th>
                                            <th class="py-2 px-3">Metode</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @forelse($pelatihan->sesis as $sesi)
                                            @php $presensi = $sesi->presensis->first(); @endphp
                                            <tr class="hover:bg-slate-50/50">
                                                <td class="py-2.5 px-3 whitespace-nowrap font-medium text-slate-800">
                                                    {{ $sesi->tanggal->format('d/m/Y') }}
                                                </td>
                                                <td class="py-2.5 px-3 font-semibold text-slate-900">
                                                    {{ $sesi->judul_sesi }}
                                                </td>
                                                <td class="py-2.5 px-3 whitespace-nowrap text-slate-500">
                                                    {{ substr((string)$sesi->jam_mulai, 0, 5) }} - {{ substr((string)$sesi->jam_selesai, 0, 5) }} WIB<br>
                                                    <span class="text-[10px] text-slate-400">{{ $sesi->lokasi }}</span>
                                                </td>
                                                <td class="py-2.5 px-3 whitespace-nowrap">
                                                    @if($presensi)
                                                        @php
                                                            $badgeColors = [
                                                                'hadir' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                                                'terlambat' => 'bg-amber-100 text-amber-800 border-amber-200',
                                                                'izin' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                                'sakit' => 'bg-purple-100 text-purple-800 border-purple-200',
                                                                'alpa' => 'bg-rose-100 text-rose-800 border-rose-200',
                                                            ];
                                                            $bc = $badgeColors[$presensi->status] ?? 'bg-slate-100 text-slate-800';
                                                        @endphp
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $bc }}">
                                                            {{ strtoupper($presensi->status) }}
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium bg-slate-100 text-slate-500">
                                                            Belum Presensi
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="py-2.5 px-3 whitespace-nowrap font-mono text-[11px] text-slate-500">
                                                    {{ $presensi ? $presensi->waktu_server->format('d/m/Y H:i:s') : '-' }}
                                                </td>
                                                <td class="py-2.5 px-3 whitespace-nowrap text-slate-500">
                                                    {{ $presensi ? strtoupper($presensi->metode) : '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="py-3 px-3 text-center text-slate-400">Belum ada sesi tercatat.</td>
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
