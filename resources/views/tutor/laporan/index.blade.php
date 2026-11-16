<x-app-layout title="Rekap & Ekspor Laporan">
    <div class="space-y-6" x-data="{ activeTab: 'rekap' }">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-200">
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Rekapitulasi & Ekspor Laporan Presensi</h1>
                <p class="text-xs text-slate-500">Susun laporan kehadiran warga belajar untuk evaluasi bulanan dan laporan dinas/Dapodik.</p>
            </div>

            <!-- Export Buttons (FR-15, Sec 9.2) -->
            <div class="flex items-center gap-2">
                <a href="{{ route('tutor.laporan.excel', request()->query()) }}"
                   class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-xs transition inline-flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Ekspor Excel (.xlsx)
                </a>

                <a href="{{ route('tutor.laporan.pdf', request()->query()) }}" target="_blank"
                   class="px-3.5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs rounded-xl shadow-xs transition inline-flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                    Cetak PDF
                </a>
            </div>
        </div>

        <!-- Filter Box -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <form method="GET" action="{{ route('tutor.laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pelatihan</label>
                    <select name="pelatihan_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-1 focus:ring-blue-600">
                        <option value="">Semua Pelatihan</option>
                        @foreach($pelatihans as $p)
                            <option value="{{ $p->id }}" {{ $pelatihanId == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Warga Belajar (Peserta)</label>
                    <select name="user_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-1 focus:ring-blue-600">
                        <option value="">Semua Peserta</option>
                        @foreach($pesertas as $peserta)
                            <option value="{{ $peserta->id }}" {{ $userId == $peserta->id ? 'selected' : '' }}>{{ $peserta->nama }} ({{ $peserta->nomor_induk ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-1 focus:ring-blue-600">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Sampai Tanggal</label>
                    <div class="flex gap-2">
                        <input type="date" name="end_date" value="{{ $endDate }}"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-1 focus:ring-blue-600">
                        <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-xl transition cursor-pointer">
                            Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tab Controls (Sec 9.2: Sheet 1 vs Sheet 2) -->
        <div class="flex items-center gap-2 border-b border-slate-200 pb-1">
            <button type="button" @click="activeTab = 'rekap'"
                    :class="activeTab === 'rekap' ? 'border-b-2 border-blue-600 text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium'"
                    class="px-4 py-2 text-xs transition cursor-pointer">
                Lembar 1: Rekapitulasi per Peserta ({{ count($rekapPeserta) }})
            </button>
            <button type="button" @click="activeTab = 'detail'"
                    :class="activeTab === 'detail' ? 'border-b-2 border-blue-600 text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium'"
                    class="px-4 py-2 text-xs transition cursor-pointer">
                Lembar 2: Log Detail Kehadiran Sesi ({{ count($detailSesi) }})
            </button>
        </div>

        <!-- Table 1: Rekapitulasi per Peserta -->
        <div x-show="activeTab === 'rekap'" class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="text-[11px] text-slate-400 uppercase bg-slate-50/70 border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4 text-center">No</th>
                            <th class="py-3 px-4">No. Induk</th>
                            <th class="py-3 px-4">Nama Warga Belajar</th>
                            <th class="py-3 px-4">Program Pelatihan</th>
                            <th class="py-3 px-4 text-center">Total Sesi</th>
                            <th class="py-3 px-4 text-center">Hadir</th>
                            <th class="py-3 px-4 text-center">Terlambat</th>
                            <th class="py-3 px-4 text-center">Izin</th>
                            <th class="py-3 px-4 text-center">Sakit</th>
                            <th class="py-3 px-4 text-center">Alpa</th>
                            <th class="py-3 px-4 text-center">% Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($rekapPeserta as $row)
                            <tr class="hover:bg-slate-50/60">
                                <td class="py-3 px-4 text-center text-slate-400">{{ $row['no'] }}</td>
                                <td class="py-3 px-4 font-mono text-[11px] text-slate-500">{{ $row['nomor_induk'] }}</td>
                                <td class="py-3 px-4 font-bold text-slate-900">{{ $row['nama'] }}</td>
                                <td class="py-3 px-4 text-slate-700">{{ $row['pelatihan'] }}</td>
                                <td class="py-3 px-4 text-center font-bold text-slate-800">{{ $row['total_sesi'] }}</td>
                                <td class="py-3 px-4 text-center text-emerald-600 font-bold">{{ $row['hadir'] }}</td>
                                <td class="py-3 px-4 text-center text-amber-600 font-bold">{{ $row['terlambat'] }}</td>
                                <td class="py-3 px-4 text-center text-blue-600">{{ $row['izin'] }}</td>
                                <td class="py-3 px-4 text-center text-purple-600">{{ $row['sakit'] }}</td>
                                <td class="py-3 px-4 text-center text-rose-600 font-bold">{{ $row['alpa'] }}</td>
                                <td class="py-3 px-4 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-black {{ $row['persentase'] >= 80 ? 'bg-emerald-100 text-emerald-800' : ($row['persentase'] >= 50 ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                        {{ $row['persentase'] }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="py-8 text-center text-slate-400">Tidak ada data rekapitulasi sesuai filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Table 2: Detail Presensi Sesi -->
        <div x-show="activeTab === 'detail'" x-cloak class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="text-[11px] text-slate-400 uppercase bg-slate-50/70 border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4">Tanggal & Jam</th>
                            <th class="py-3 px-4">Judul Sesi & Lokasi</th>
                            <th class="py-3 px-4">Warga Belajar</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Waktu Server</th>
                            <th class="py-3 px-4">Metode</th>
                            <th class="py-3 px-4">Catatan Koreksi (Audit)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($detailSesi as $row)
                            <tr class="hover:bg-slate-50/60">
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <div class="font-medium text-slate-900">{{ $row['tanggal'] }}</div>
                                    <div class="text-[10px] text-slate-500">{{ $row['jam_sesi'] }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-bold text-slate-900">{{ $row['judul_sesi'] }}</div>
                                    <div class="text-[10px] text-slate-500">{{ $row['lokasi'] }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-bold text-slate-900">{{ $row['nama'] }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono">{{ $row['nomor_induk'] }}</div>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    @php
                                        $s = strtolower($row['status']);
                                        $colors = [
                                            'hadir' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            'terlambat' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'izin' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'sakit' => 'bg-purple-100 text-purple-800 border-purple-200',
                                            'alpa' => 'bg-rose-100 text-rose-800 border-rose-200',
                                        ];
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase border {{ $colors[$s] ?? 'bg-slate-100 text-slate-700' }}">
                                        {{ $row['status'] }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 font-mono text-[11px] text-slate-600 whitespace-nowrap">
                                    {{ $row['waktu_server'] }}
                                </td>
                                <td class="py-3 px-4 uppercase text-[10px] text-slate-500 whitespace-nowrap">
                                    {{ $row['metode'] }}
                                </td>
                                <td class="py-3 px-4 text-slate-600 text-[11px] max-w-xs">
                                    {{ $row['catatan_koreksi'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">Tidak ada log presensi sesuai filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
