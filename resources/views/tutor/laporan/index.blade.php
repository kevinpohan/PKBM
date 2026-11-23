<x-app-layout title="Rekap & Ekspor Laporan">
    <div class="flex flex-col gap-6" x-data="{ activeTab: 'rekap' }">

        <!-- Top Header & Export Actions (Stitch Header) -->
        <div class="bg-surface-container-lowest p-5 sm:p-6 rounded-2xl shadow-xs border border-outline-variant/30 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-primary"></span>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-on-surface tracking-tight">Rekapitulasi & Ekspor Laporan Presensi</h1>
                </div>
                <p class="text-xs text-on-surface-variant mt-1">Susun laporan kehadiran warga belajar untuk evaluasi kelas dan arsip resmi dinas/Dapodik.</p>
            </div>

            <!-- Export Buttons (FR-15) -->
            <div class="flex items-center gap-2.5 flex-wrap self-start md:self-auto">
                <a href="{{ route('tutor.laporan.excel', request()->query()) }}"
                   class="px-4 py-2.5 bg-secondary hover:bg-secondary/90 text-on-secondary font-bold text-xs rounded-xl shadow-xs transition inline-flex items-center gap-2 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">table_view</span>
                    <span>Ekspor Excel (.xlsx)</span>
                </a>

                <a href="{{ route('tutor.laporan.pdf', request()->query()) }}" target="_blank"
                   class="px-4 py-2.5 bg-primary hover:bg-primary-container text-on-primary font-bold text-xs rounded-xl shadow-xs transition inline-flex items-center gap-2 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                    <span>Cetak PDF Resmi</span>
                </a>
            </div>
        </div>

        <!-- Filter Box (Stitch Style) -->
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 p-5 shadow-xs">
            <form method="GET" action="{{ route('tutor.laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-on-surface">Pilih Pelatihan</label>
                    <select name="pelatihan_id" class="w-full px-3 py-2.5 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface font-medium focus:ring-2 focus:ring-primary focus:outline-none">
                        <option value="">Semua Pelatihan</option>
                        @foreach($pelatihans as $p)
                            <option value="{{ $p->id }}" {{ (string) $pelatihanId === (string) $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-on-surface">Warga Belajar (Peserta)</label>
                    <select name="user_id" class="w-full px-3 py-2.5 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface font-medium focus:ring-2 focus:ring-primary focus:outline-none">
                        <option value="">Semua Peserta</option>
                        @foreach($pesertas as $peserta)
                            <option value="{{ $peserta->id }}" {{ (string) $userId === (string) $peserta->id ? 'selected' : '' }}>{{ $peserta->nama }} ({{ $peserta->nomor_induk ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-on-surface">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                           class="w-full px-3 py-2.5 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface font-medium focus:ring-2 focus:ring-primary focus:outline-none">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-on-surface">Sampai Tanggal</label>
                    <div class="flex gap-2">
                        <input type="date" name="end_date" value="{{ $endDate }}"
                               class="w-full px-3 py-2.5 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface font-medium focus:ring-2 focus:ring-primary focus:outline-none">
                        <button type="submit" class="px-4 py-2.5 bg-primary hover:bg-primary-container text-on-primary font-bold text-xs rounded-xl shadow-xs transition flex items-center justify-center cursor-pointer">
                            Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tab Controls (Stitch Lembar 1 vs Lembar 2) -->
        <div class="flex items-center gap-2 border-b border-outline-variant/20 pb-1">
            <button type="button" @click="activeTab = 'rekap'"
                    :class="activeTab === 'rekap' ? 'border-b-2 border-primary text-primary font-extrabold' : 'text-on-surface-variant hover:text-on-surface font-semibold'"
                    class="px-4 py-2.5 text-xs transition cursor-pointer flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">group</span>
                <span>Lembar 1: Rekapitulasi per Peserta ({{ count($rekapPeserta) }})</span>
            </button>
            <button type="button" @click="activeTab = 'detail'"
                    :class="activeTab === 'detail' ? 'border-b-2 border-primary text-primary font-extrabold' : 'text-on-surface-variant hover:text-on-surface font-semibold'"
                    class="px-4 py-2.5 text-xs transition cursor-pointer flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">list_alt</span>
                <span>Lembar 2: Log Detail Sesi ({{ count($detailSesi) }})</span>
            </button>
        </div>

        <!-- Table 1: Rekapitulasi per Peserta -->
        <div x-show="activeTab === 'rekap'" class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-on-surface">
                    <thead class="text-[11px] text-on-surface-variant uppercase bg-surface-container-low border-b border-outline-variant/20">
                        <tr>
                            <th class="py-3 px-3 text-center">No</th>
                            <th class="py-3 px-3">No. Induk</th>
                            <th class="py-3 px-3">Nama Warga Belajar</th>
                            <th class="py-3 px-3">Program Pelatihan</th>
                            <th class="py-3 px-3 text-center">Total Sesi</th>
                            <th class="py-3 px-3 text-center">Hadir</th>
                            <th class="py-3 px-3 text-center">Terlambat</th>
                            <th class="py-3 px-3 text-center">Izin</th>
                            <th class="py-3 px-3 text-center">Sakit</th>
                            <th class="py-3 px-3 text-center">Alpa</th>
                            <th class="py-3 px-3 text-center">% Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/15">
                        @forelse($rekapPeserta as $idx => $r)
                            @php
                                $persen = $r['persentase'];
                                $isHigh = $persen >= 80;
                                $isMid = $persen >= 50 && $persen < 80;
                            @endphp
                            <tr class="hover:bg-surface-container-low/50 transition">
                                <td class="py-3 px-3 text-center text-on-surface-variant">{{ $idx + 1 }}</td>
                                <td class="py-3 px-3 font-mono text-on-surface-variant">{{ $r['nomor_induk'] ?: '-' }}</td>
                                <td class="py-3 px-3 font-bold text-on-surface">{{ $r['nama'] }}</td>
                                <td class="py-3 px-3 text-on-surface-variant font-medium">{{ $r['pelatihan'] }}</td>
                                <td class="py-3 px-3 text-center font-bold text-on-surface">{{ $r['total_sesi'] }}</td>
                                <td class="py-3 px-3 text-center font-bold text-secondary">{{ $r['hadir'] }}</td>
                                <td class="py-3 px-3 text-center font-bold text-amber-600">{{ $r['terlambat'] }}</td>
                                <td class="py-3 px-3 text-center font-bold text-primary">{{ $r['izin'] }}</td>
                                <td class="py-3 px-3 text-center font-bold text-purple-700">{{ $r['sakit'] }}</td>
                                <td class="py-3 px-3 text-center font-bold text-error">{{ $r['alpa'] }}</td>
                                <td class="py-3 px-3 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-black {{ $isHigh ? 'bg-secondary-container text-on-secondary-container' : ($isMid ? 'bg-amber-100 text-amber-900' : 'bg-error-container text-on-error-container') }}">
                                        {{ $persen }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="py-8 text-center text-on-surface-variant italic">
                                    Tidak ada data kehadiran yang sesuai dengan filter yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Table 2: Detail Sesi -->
        <div x-show="activeTab === 'detail'" x-cloak class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-on-surface">
                    <thead class="text-[11px] text-on-surface-variant uppercase bg-surface-container-low border-b border-outline-variant/20">
                        <tr>
                            <th class="py-3 px-3 text-center">No</th>
                            <th class="py-3 px-3">Tanggal</th>
                            <th class="py-3 px-3">Judul Sesi</th>
                            <th class="py-3 px-3">Pelatihan</th>
                            <th class="py-3 px-3">Warga Belajar</th>
                            <th class="py-3 px-3 text-center">Status</th>
                            <th class="py-3 px-3">Waktu Presensi</th>
                            <th class="py-3 px-3">Metode</th>
                            <th class="py-3 px-3">Catatan / Koreksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/15">
                        @forelse($detailSesi as $idx => $d)
                            @php
                                $statusLower = strtolower($d['status'] ?? '');
                            @endphp
                            <tr class="hover:bg-surface-container-low/50 transition">
                                <td class="py-3 px-3 text-center text-on-surface-variant">{{ $idx + 1 }}</td>
                                <td class="py-3 px-3 whitespace-nowrap text-on-surface font-medium">{{ $d['tanggal'] }}</td>
                                <td class="py-3 px-3 font-bold text-on-surface">{{ $d['judul_sesi'] }}</td>
                                <td class="py-3 px-3 text-on-surface-variant">{{ $d['pelatihan'] }}</td>
                                <td class="py-3 px-3 font-semibold text-on-surface">
                                    <div>{{ $d['nama'] }}</div>
                                    <div class="text-[10px] text-on-surface-variant font-mono">{{ $d['nomor_induk'] }}</div>
                                </td>
                                <td class="py-3 px-3 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                        {{ $statusLower === 'hadir' ? 'bg-secondary-container text-on-secondary-container' :
                                           ($statusLower === 'terlambat' ? 'bg-amber-100 text-amber-900' :
                                           ($statusLower === 'izin' ? 'bg-primary-fixed text-on-primary-fixed' :
                                           ($statusLower === 'sakit' ? 'bg-purple-100 text-purple-900' : 'bg-error-container text-on-error-container'))) }}">
                                        {{ $d['status'] }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 font-mono text-on-surface text-[11px]">{{ $d['waktu_server'] }}</td>
                                <td class="py-3 px-3 uppercase text-[10px] text-on-surface-variant font-semibold">{{ $d['metode'] }}</td>
                                <td class="py-3 px-3 text-on-surface-variant italic max-w-xs truncate" title="{{ $d['catatan_koreksi'] }}">{{ $d['catatan_koreksi'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-8 text-center text-on-surface-variant italic">
                                    Tidak ada log detail presensi yang sesuai dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
