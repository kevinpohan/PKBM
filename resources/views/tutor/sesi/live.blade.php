<x-app-layout title="Pemantauan Live Presensi Sesi">
    <div class="flex flex-col gap-6" x-data="liveMonitoringHandler({{ $sesi->id }})" x-init="init()">

        <!-- 1. Header Bar (Stitch Component 1) -->
        <div class="bg-surface-container-lowest p-5 sm:p-6 rounded-2xl shadow-xs border border-outline-variant/30 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4">
            <div class="flex flex-col gap-1.5">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-3 py-0.5 rounded-full bg-secondary-container text-on-secondary-container text-xs font-bold flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                        Sesi Berlangsung (Live)
                    </span>
                    <span class="text-xs text-on-surface-variant flex items-center gap-1 font-medium">
                        <span class="material-symbols-outlined text-[16px] text-primary">location_on</span>
                        {{ $sesi->lokasi }}
                    </span>
                    <span class="text-xs text-on-surface-variant flex items-center gap-1 font-medium">
                        <span class="material-symbols-outlined text-[16px]">schedule</span>
                        {{ substr((string)$sesi->jam_mulai, 0, 5) }} - {{ substr((string)$sesi->jam_selesai, 0, 5) }} WIB
                    </span>
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-on-surface tracking-tight mt-0.5">{{ $sesi->judul_sesi }}</h1>
                <p class="text-xs text-on-surface-variant font-medium">{{ $sesi->pelatihan->nama }} • {{ $sesi->tanggal->translatedFormat('l, d F Y') }}</p>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap self-start xl:self-auto">
                <a href="{{ route('tutor.sesi.qr', $sesi) }}" target="_blank"
                   class="px-4 py-2.5 rounded-xl bg-primary hover:bg-primary-container text-on-primary font-bold text-xs shadow-xs transition flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">qr_code_2</span>
                    <span>Tampilkan Layar QR</span>
                </a>
                <button type="button" @click="fetchData()"
                        class="p-2.5 rounded-xl bg-surface-container hover:bg-surface-container-high text-on-surface transition border border-outline-variant/30"
                        title="Perbarui Data">
                    <span :class="isLoading ? 'animate-spin' : ''" class="material-symbols-outlined text-[20px]">refresh</span>
                </button>
            </div>
        </div>

        <!-- 2. Bento Grid Metric Cards (Stitch Component 2) -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
            <!-- Rasio Kehadiran -->
            <div class="bg-surface-container-lowest p-4 rounded-2xl shadow-xs border border-outline-variant/30 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-on-surface-variant uppercase">Rasio Hadir</span>
                    <span class="material-symbols-outlined text-primary text-[20px]">pie_chart</span>
                </div>
                <div class="mt-2">
                    <span class="text-2xl sm:text-3xl font-black text-primary" x-text="`${ratio}%`">{{ $ratio }}%</span>
                </div>
                <div class="w-full bg-surface-container-high h-1.5 rounded-full mt-2.5 overflow-hidden">
                    <div class="bg-primary h-full rounded-full transition-all duration-500" :style="'width: ' + ratio + '%'"></div>
                </div>
            </div>

            <!-- Total Terdaftar -->
            <div class="bg-surface-container-lowest p-4 rounded-2xl shadow-xs border border-outline-variant/30 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-on-surface-variant uppercase">Terdaftar</span>
                    <span class="material-symbols-outlined text-on-surface text-[20px]">groups</span>
                </div>
                <div class="mt-2">
                    <span class="text-2xl sm:text-3xl font-black text-on-surface" x-text="totalEnrolled">{{ $total_enrolled }}</span>
                </div>
                <span class="text-[11px] text-on-surface-variant mt-2">Warga Belajar</span>
            </div>

            <!-- Hadir Tepat -->
            <div class="bg-surface-container-lowest p-4 rounded-2xl shadow-xs border border-outline-variant/30 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-secondary uppercase">Hadir Tepat</span>
                    <span class="material-symbols-outlined text-secondary text-[20px]">verified</span>
                </div>
                <div class="mt-2">
                    <span class="text-2xl sm:text-3xl font-black text-secondary" x-text="countHadir">{{ $count_hadir }}</span>
                </div>
                <span class="text-[11px] text-secondary font-bold mt-2">Tepat Waktu</span>
            </div>

            <!-- Terlambat -->
            <div class="bg-surface-container-lowest p-4 rounded-2xl shadow-xs border border-outline-variant/30 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-amber-600 uppercase">Terlambat</span>
                    <span class="material-symbols-outlined text-amber-600 text-[20px]">schedule</span>
                </div>
                <div class="mt-2">
                    <span class="text-2xl sm:text-3xl font-black text-amber-600" x-text="countTerlambat">{{ $count_terlambat }}</span>
                </div>
                <span class="text-[11px] text-amber-700 font-medium mt-2">> {{ $sesi->batas_terlambat_menit }} mnt</span>
            </div>

            <!-- Izin / Sakit -->
            <div class="bg-surface-container-lowest p-4 rounded-2xl shadow-xs border border-outline-variant/30 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-tertiary-container uppercase">Izin / Sakit</span>
                    <span class="material-symbols-outlined text-tertiary-container text-[20px]">clinical_notes</span>
                </div>
                <div class="mt-2">
                    <span class="text-2xl sm:text-3xl font-black text-tertiary-container" x-text="countIzin + countSakit">{{ $count_izin + $count_sakit }}</span>
                </div>
                <span class="text-[11px] text-on-surface-variant mt-2">Terverifikasi</span>
            </div>

            <!-- Belum Hadir / Alpa -->
            <div class="bg-surface-container-lowest p-4 rounded-2xl shadow-xs border border-outline-variant/30 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-error uppercase">Belum Hadir</span>
                    <span class="material-symbols-outlined text-error text-[20px]">person_off</span>
                </div>
                <div class="mt-2">
                    <span class="text-2xl sm:text-3xl font-black text-error" x-text="countBelumAbsen + countAlpa">{{ $count_belum_absen + $count_alpa }}</span>
                </div>
                <span class="text-[11px] text-error font-medium mt-2">Menunggu Presensi</span>
            </div>
        </div>

        <!-- 3. Attendees Table & Quick Filter (Stitch Component 3) -->
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-xs overflow-hidden">
            
            <div class="p-4 sm:p-5 bg-surface-container-low/60 border-b border-outline-variant/20 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">list_alt</span>
                    <span class="text-xs font-bold text-on-surface uppercase tracking-wider">Daftar Kehadiran Warga Belajar</span>
                    <span class="text-[11px] text-on-surface-variant">(Auto-sync 5 detik)</span>
                </div>

                <div class="flex items-center gap-2 flex-wrap">
                    <select x-model="filterStatus"
                            class="px-3 py-1.5 bg-surface-container-lowest border border-outline-variant/40 rounded-xl text-xs text-on-surface font-semibold focus:ring-2 focus:ring-primary focus:outline-none">
                        <option value="">Semua Status</option>
                        <option value="hadir">Hadir (Tepat)</option>
                        <option value="terlambat">Terlambat</option>
                        <option value="izin">Izin</option>
                        <option value="sakit">Sakit</option>
                        <option value="alpa">Alpa</option>
                        <option value="belum_absen">Belum Absen</option>
                    </select>

                    <div class="relative">
                        <input type="text" x-model="searchQuery" placeholder="Cari nama / NISN..."
                               class="pl-8 pr-3 py-1.5 bg-surface-container-lowest border border-outline-variant/40 rounded-xl text-xs text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary focus:outline-none">
                        <span class="material-symbols-outlined absolute left-2 top-1.5 text-[18px] text-outline pointer-events-none">search</span>
                    </div>
                </div>
            </div>

            <!-- Table responsive container -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-on-surface">
                    <thead class="text-[11px] text-on-surface-variant uppercase bg-surface-container-low border-b border-outline-variant/20">
                        <tr>
                            <th class="py-3 px-4">No. Induk</th>
                            <th class="py-3 px-4">Nama Warga Belajar</th>
                            <th class="py-3 px-4">No. HP</th>
                            <th class="py-3 px-4">Status Presensi</th>
                            <th class="py-3 px-4">Waktu</th>
                            <th class="py-3 px-4">Metode</th>
                            <th class="py-3 px-4 text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/15">
                        <template x-for="item in filteredAttendees" :key="item.user_id">
                            <tr class="hover:bg-surface-container-low/50 transition">
                                <td class="py-3 px-4 font-mono text-[11px] text-on-surface-variant" x-text="item.nomor_induk"></td>
                                <td class="py-3 px-4 font-bold text-on-surface" x-text="item.nama"></td>
                                <td class="py-3 px-4 text-on-surface-variant" x-text="item.no_hp || '-'"></td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase"
                                          :class="{
                                              'bg-secondary-container text-on-secondary-container': item.status === 'hadir',
                                              'bg-amber-100 text-amber-900': item.status === 'terlambat',
                                              'bg-primary-fixed text-on-primary-fixed': item.status === 'izin',
                                              'bg-purple-100 text-purple-900': item.status === 'sakit',
                                              'bg-error-container text-on-error-container': item.status === 'alpa',
                                              'bg-surface-container text-on-surface-variant': item.status === 'belum_absen'
                                          }">
                                        <span class="w-1.5 h-1.5 rounded-full"
                                              :class="{
                                                  'bg-secondary': item.status === 'hadir',
                                                  'bg-amber-500': item.status === 'terlambat',
                                                  'bg-primary': item.status === 'izin',
                                                  'bg-purple-600': item.status === 'sakit',
                                                  'bg-error': item.status === 'alpa',
                                                  'bg-outline': item.status === 'belum_absen'
                                              }"></span>
                                        <span x-text="item.status.replace('_', ' ')"></span>
                                    </span>
                                </td>
                                <td class="py-3 px-4 font-mono text-on-surface" x-text="item.waktu || '-'"></td>
                                <td class="py-3 px-4 uppercase text-[10px] text-on-surface-variant font-semibold" x-text="item.metode || '-'"></td>
                                <td class="py-3 px-4 text-right">
                                    <button type="button" @click="openKoreksiModal(item)"
                                            class="px-3 py-1.5 bg-surface-container hover:bg-primary-container hover:text-on-primary text-primary font-bold text-[11px] rounded-xl transition border border-outline-variant/30 cursor-pointer">
                                        Koreksi
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="p-3.5 bg-surface-container-low border-t border-outline-variant/20 flex items-center justify-between text-[11px] text-on-surface-variant">
                <span>Total peserta terdaftar: <strong x-text="filteredAttendees.length"></strong></span>
                <span>Pembaruan terakhir: <span class="font-mono text-on-surface font-semibold" x-text="lastUpdated">{{ $last_updated }}</span></span>
            </div>
        </div>

        <!-- 4. Modal Koreksi Presensi (Stitch Modal Pattern - FR-13) -->
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-xs" @click="showModal = false"></div>
            
            <div class="relative bg-surface-container-lowest rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-outline-variant/30 z-10">
                <div class="flex items-center justify-between pb-3 border-b border-outline-variant/20 mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-primary-fixed text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">edit_note</span>
                        </div>
                        <h3 class="text-base font-bold text-on-surface">Koreksi Status Presensi</h3>
                    </div>
                    <button type="button" @click="showModal = false" class="p-1 rounded-lg text-on-surface-variant hover:bg-surface-container">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                <form method="POST" action="{{ route('tutor.koreksi.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="sesi_id" value="{{ $sesi->id }}">
                    <input type="hidden" name="user_id" :value="modalItem ? modalItem.user_id : ''">

                    <div class="p-3 rounded-xl bg-surface-container-low border border-outline-variant/20">
                        <span class="text-[11px] text-on-surface-variant block">Warga Belajar:</span>
                        <span class="text-sm font-bold text-on-surface" x-text="modalItem ? modalItem.nama : ''"></span>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-on-surface">Ubah Status Menjadi:</label>
                        <select name="status_sesudah" required
                                class="w-full px-3.5 py-2.5 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs font-bold text-on-surface focus:ring-2 focus:ring-primary focus:outline-none">
                            <option value="hadir">HADIR (Tepat Waktu)</option>
                            <option value="terlambat">TERLAMBAT</option>
                            <option value="izin">IZIN</option>
                            <option value="sakit">SAKIT</option>
                            <option value="alpa">ALPA</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-on-surface flex items-center justify-between">
                            <span>Alasan Koreksi</span>
                            <span class="text-[11px] text-error font-semibold">* Wajib Audit</span>
                        </label>
                        <textarea name="alasan" required rows="3" minlength="5"
                                  placeholder="Contoh: Peserta hadir langsung di kelas namun kamera HP rusak, atau konfirmasi izin sakit via WA."
                                  class="w-full p-3 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary focus:outline-none"></textarea>
                        <span class="text-[10px] text-on-surface-variant">Setiap koreksi akan disimpan permanen pada Log Audit sistem demi akuntabilitas Dapodik.</span>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-outline-variant/20">
                        <button type="button" @click="showModal = false"
                                class="px-4 py-2.5 bg-surface-container hover:bg-surface-container-high text-on-surface text-xs font-bold rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 bg-primary hover:bg-primary-container text-on-primary text-xs font-bold rounded-xl shadow-xs transition">
                            Simpan Koreksi
                        </button>
                    </div>
                </form>
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
