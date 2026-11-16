<x-app-layout title="Monitoring Live Presensi Sesi">
    <div class="space-y-6" x-data="liveMonitoringHandler({{ $sesi->id }})" x-init="init()">

        <!-- Header Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                        Live Monitoring
                    </span>
                    <span class="text-xs text-slate-500 font-medium">{{ $sesi->pelatihan->nama }}</span>
                </div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">{{ $sesi->judul_sesi }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    {{ $sesi->tanggal->format('d/m/Y') }} &bull; {{ substr((string)$sesi->jam_mulai, 0, 5) }} - {{ substr((string)$sesi->jam_selesai, 0, 5) }} WIB &bull; {{ $sesi->lokasi }}
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('tutor.sesi.qr', $sesi) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                    Buka QR Layar Penuh
                </a>
                <button type="button" @click="fetchData()" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition" title="Muat Ulang Data">
                    <svg :class="isLoading ? 'animate-spin' : ''" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                </button>
            </div>
        </div>

        <!-- Metric Stat Cards (Live Attendance Ratio) -->
        <div class="grid grid-cols-2 lg:grid-cols-6 gap-3">
            
            <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs">
                <span class="text-[10px] text-slate-400 uppercase font-bold block mb-1">Rasio Kehadiran</span>
                <div class="text-2xl font-black text-blue-600" x-text="`${ratio}%`">{{ $ratio }}%</div>
                <span class="text-[10px] text-slate-400"><span x-text="totalMasuk">{{ $total_masuk }}</span> dari <span x-text="totalEnrolled">{{ $total_enrolled }}</span></span>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs">
                <span class="text-[10px] text-slate-400 uppercase font-bold block mb-1">Hadir Tepat</span>
                <div class="text-2xl font-black text-emerald-600" x-text="countHadir">{{ $count_hadir }}</div>
                <span class="text-[10px] text-emerald-700 font-medium">Tepat Waktu</span>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs">
                <span class="text-[10px] text-slate-400 uppercase font-bold block mb-1">Terlambat</span>
                <div class="text-2xl font-black text-amber-600" x-text="countTerlambat">{{ $count_terlambat }}</div>
                <span class="text-[10px] text-amber-700 font-medium">> {{ $sesi->batas_terlambat_menit }} mnt</span>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs">
                <span class="text-[10px] text-slate-400 uppercase font-bold block mb-1">Izin / Sakit</span>
                <div class="text-2xl font-black text-indigo-600" x-text="countIzin + countSakit">{{ $count_izin + $count_sakit }}</div>
                <span class="text-[10px] text-slate-400">Berketerangan</span>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs">
                <span class="text-[10px] text-slate-400 uppercase font-bold block mb-1">Alpa</span>
                <div class="text-2xl font-black text-rose-600" x-text="countAlpa">{{ $count_alpa }}</div>
                <span class="text-[10px] text-slate-400">Tanpa kabar</span>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs">
                <span class="text-[10px] text-slate-400 uppercase font-bold block mb-1">Belum Absen</span>
                <div class="text-2xl font-black text-slate-700" x-text="countBelumAbsen">{{ $count_belum_absen }}</div>
                <span class="text-[10px] text-slate-400">Menunggu scan</span>
            </div>

        </div>

        <!-- Filter & Attendees Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            
            <div class="p-4 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Daftar Warga Belajar</span>
                    <span class="text-[10px] text-slate-400">(Pembaruan otomatis tiap 5 detik)</span>
                </div>

                <div class="flex items-center gap-2">
                    <select x-model="filterStatus" class="px-2.5 py-1.5 bg-white border border-slate-300 rounded-xl text-xs text-slate-700 focus:ring-1 focus:ring-blue-600">
                        <option value="">Semua Status</option>
                        <option value="hadir">Hadir</option>
                        <option value="terlambat">Terlambat</option>
                        <option value="izin">Izin</option>
                        <option value="sakit">Sakit</option>
                        <option value="alpa">Alpa</option>
                        <option value="belum_absen">Belum Absen</option>
                    </select>

                    <input type="text" x-model="searchQuery" placeholder="Cari nama..."
                           class="px-2.5 py-1.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-1 focus:ring-blue-600">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="text-[11px] text-slate-400 uppercase bg-slate-50/50 border-b border-slate-100">
                        <tr>
                            <th class="py-2.5 px-3">No. Induk</th>
                            <th class="py-2.5 px-3">Nama Warga Belajar</th>
                            <th class="py-2.5 px-3">No. HP</th>
                            <th class="py-2.5 px-3">Status Saat Ini</th>
                            <th class="py-2.5 px-3">Waktu Presensi</th>
                            <th class="py-2.5 px-3">Metode</th>
                            <th class="py-2.5 px-3 text-right">Koreksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="item in filteredAttendees" :key="item.user_id">
                            <tr class="hover:bg-slate-50/60">
                                <td class="py-3 px-3 font-mono text-[11px] text-slate-500" x-text="item.nomor_induk"></td>
                                <td class="py-3 px-3 font-bold text-slate-900" x-text="item.nama"></td>
                                <td class="py-3 px-3 text-slate-500" x-text="item.no_hp"></td>
                                <td class="py-3 px-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase border"
                                          :class="{
                                              'bg-emerald-100 text-emerald-800 border-emerald-200': item.status === 'hadir',
                                              'bg-amber-100 text-amber-800 border-amber-200': item.status === 'terlambat',
                                              'bg-blue-100 text-blue-800 border-blue-200': item.status === 'izin',
                                              'bg-purple-100 text-purple-800 border-purple-200': item.status === 'sakit',
                                              'bg-rose-100 text-rose-800 border-rose-200': item.status === 'alpa',
                                              'bg-slate-100 text-slate-600 border-slate-200': item.status === 'belum_absen'
                                          }"
                                          x-text="item.status.replace('_', ' ')">
                                    </span>
                                </td>
                                <td class="py-3 px-3 font-mono text-slate-700" x-text="item.waktu"></td>
                                <td class="py-3 px-3 uppercase text-[10px] text-slate-500" x-text="item.metode"></td>
                                <td class="py-3 px-3 text-right">
                                    <button type="button" @click="openKoreksiModal(item)"
                                            class="px-2.5 py-1 bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-700 font-semibold text-[11px] rounded-lg transition border border-slate-200 cursor-pointer">
                                        Koreksi
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="p-3 bg-slate-50 border-t border-slate-100 text-[11px] text-slate-400 text-right">
                Pembaruan terakhir: <span class="font-mono text-slate-600" x-text="lastUpdated">{{ $last_updated }}</span>
            </div>

        </div>

        <!-- Modal Koreksi Presensi (FR-13) -->
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(15, 23, 42, 0.6);">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div @click.away="showModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 relative">
                    
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                        <h3 class="text-base font-bold text-slate-900">Koreksi Status Presensi</h3>
                        <button type="button" @click="showModal = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                    </div>

                    <form method="POST" action="{{ route('tutor.koreksi.store') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="sesi_id" value="{{ $sesi->id }}">
                        <input type="hidden" name="user_id" :value="modalItem ? modalItem.user_id : ''">

                        <div>
                            <span class="text-xs text-slate-500 block">Nama Peserta:</span>
                            <span class="text-sm font-bold text-slate-800" x-text="modalItem ? modalItem.nama : ''"></span>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Ubah Status Menjadi:</label>
                            <select name="status_sesudah" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-blue-600">
                                <option value="hadir">HADIR</option>
                                <option value="terlambat">TERLAMBAT</option>
                                <option value="izin">IZIN</option>
                                <option value="sakit">SAKIT</option>
                                <option value="alpa">ALPA</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Catatan Alasan Koreksi <span class="text-rose-500 font-bold">* (Wajib diisi untuk Log Audit)</span>
                            </label>
                            <textarea name="alasan" required rows="3" minlength="5"
                                      placeholder="Contoh: Peserta hadir langsung di kelas namun kamera HP rusak, atau konfirmasi izin sakit via WA."
                                      class="w-full p-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-hidden"></textarea>
                            <span class="text-[10px] text-slate-400">Setiap koreksi akan disimpan permanen pada Log Audit demi akuntabilitas data.</span>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button type="button" @click="showModal = false" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-xs transition">
                                Simpan Koreksi
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>

    <script>
        function liveMonitoringHandler(sesiId) {
            return {
                sesiId: sesiId,
                totalEnrolled: {{ $total_enrolled }},
                countHadir: {{ $count_hadir }},
                countTerlambat: {{ $count_terlambat }},
                countIzin: {{ $count_izin }},
                countSakit: {{ $count_sakit }},
                countAlpa: {{ $count_alpa }},
                countBelumAbsen: {{ $count_belum_absen }},
                totalMasuk: {{ $total_masuk }},
                ratio: {{ $ratio }},
                attendees: @json($attendees),
                lastUpdated: '{{ $last_updated }}',
                isLoading: false,
                filterStatus: '',
                searchQuery: '',
                showModal: false,
                modalItem: null,

                init() {
                    // Poll live data every 5 seconds
                    setInterval(() => {
                        this.fetchData();
                    }, 5000);
                },

                async fetchData() {
                    this.isLoading = true;
                    try {
                        const res = await fetch(`/tutor/sesi/${this.sesiId}/live-data`);
                        const data = await res.json();
                        this.totalEnrolled = data.total_enrolled;
                        this.countHadir = data.count_hadir;
                        this.countTerlambat = data.count_terlambat;
                        this.countIzin = data.count_izin;
                        this.countSakit = data.count_sakit;
                        this.countAlpa = data.count_alpa;
                        this.countBelumAbsen = data.count_belum_absen;
                        this.totalMasuk = data.total_masuk;
                        this.ratio = data.ratio;
                        this.attendees = data.attendees;
                        this.lastUpdated = data.last_updated;
                    } catch (e) {
                        console.error('Polling error', e);
                    } finally {
                        this.isLoading = false;
                    }
                },

                get filteredAttendees() {
                    return this.attendees.filter(item => {
                        const matchStatus = !this.filterStatus || item.status === this.filterStatus;
                        const matchQuery = !this.searchQuery ||
                            item.nama.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            item.nomor_induk.toLowerCase().includes(this.searchQuery.toLowerCase());
                        return matchStatus && matchQuery;
                    });
                },

                openKoreksiModal(item) {
                    this.modalItem = item;
                    this.showModal = true;
                }
            }
        }
    </script>
</x-app-layout>
