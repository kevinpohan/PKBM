<x-app-layout title="Koreksi Presensi">
    <div class="flex flex-col gap-6" x-data="{ showModal: false, selectedRecord: null }">

        <!-- Top Header Bar -->
        <div class="bg-surface-container-lowest p-5 sm:p-6 rounded-2xl shadow-xs border border-outline-variant/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-primary"></span>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-on-surface tracking-tight">Koreksi Presensi & Kehadiran</h1>
                </div>
                <p class="text-xs text-on-surface-variant mt-1">Ubah status presensi peserta dengan kewajiban mengisi alasan untuk Log Audit sistem.</p>
            </div>
            
            <a href="{{ route('tutor.audit-logs') }}"
               class="px-3.5 py-2.5 bg-surface-container hover:bg-surface-container-high text-on-surface font-bold text-xs rounded-xl border border-outline-variant/30 transition inline-flex items-center gap-1.5 self-start sm:self-auto">
                <span class="material-symbols-outlined text-[18px] text-secondary">history_edu</span>
                <span>Log Audit Lengkap &rarr;</span>
            </a>
        </div>

        <!-- Filter Bar -->
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 p-4 sm:p-5 shadow-xs">
            <form method="GET" action="{{ route('tutor.koreksi.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-bold text-on-surface">Pilih Pelatihan</label>
                    <select name="pelatihan_id" onchange="this.form.submit()"
                            class="w-full px-3 py-2 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface font-medium focus:ring-2 focus:ring-primary focus:outline-none">
                        <option value="">Semua Pelatihan</option>
                        @foreach($pelatihans as $p)
                            <option value="{{ $p->id }}" {{ ($pelatihanId == $p->id) ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-bold text-on-surface">Pilih Sesi</label>
                    <select name="sesi_id" onchange="this.form.submit()"
                            class="w-full px-3 py-2 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface font-medium focus:ring-2 focus:ring-primary focus:outline-none">
                        <option value="">Semua Sesi</option>
                        @foreach($sesis as $s)
                            <option value="{{ $s->id }}" {{ ($sesiId == $s->id) ? 'selected' : '' }}>{{ $s->judul_sesi }} ({{ $s->tanggal->format('d/m') }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-bold text-on-surface">Status Kehadiran</label>
                    <select name="status" onchange="this.form.submit()"
                            class="w-full px-3 py-2 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface font-medium focus:ring-2 focus:ring-primary focus:outline-none">
                        <option value="">Semua Status</option>
                        <option value="hadir" {{ ($status === 'hadir') ? 'selected' : '' }}>Hadir</option>
                        <option value="terlambat" {{ ($status === 'terlambat') ? 'selected' : '' }}>Terlambat</option>
                        <option value="izin" {{ ($status === 'izin') ? 'selected' : '' }}>Izin</option>
                        <option value="sakit" {{ ($status === 'sakit') ? 'selected' : '' }}>Sakit</option>
                        <option value="alpa" {{ ($status === 'alpa') ? 'selected' : '' }}>Alpa</option>
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-bold text-on-surface">Cari Peserta</label>
                    <div class="flex gap-2">
                        <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Nama / No. Induk..."
                               class="w-full px-3 py-2 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary focus:outline-none">
                        <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-container text-on-primary font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
                            Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Attendees Presensi Table -->
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-on-surface">
                    <thead class="text-[11px] text-on-surface-variant uppercase bg-surface-container-low border-b border-outline-variant/20">
                        <tr>
                            <th class="py-3 px-4">Nama Peserta</th>
                            <th class="py-3 px-4">Program & Sesi</th>
                            <th class="py-3 px-4">Tanggal Kelas</th>
                            <th class="py-3 px-4">Status Saat Ini</th>
                            <th class="py-3 px-4">Waktu Presensi</th>
                            <th class="py-3 px-4">Metode</th>
                            <th class="py-3 px-4 text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/15">
                        @forelse($presensis as $presensi)
                            <tr class="hover:bg-surface-container-low/50 transition">
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-on-surface">{{ $presensi->user->nama ?? '-' }}</div>
                                    <div class="text-[11px] font-mono text-on-surface-variant">{{ $presensi->user->nomor_induk ?? '-' }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-semibold text-on-surface">{{ $presensi->sesi->judul_sesi ?? '-' }}</div>
                                    <div class="text-[11px] text-on-surface-variant">{{ $presensi->sesi->pelatihan->nama ?? '-' }}</div>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-on-surface">
                                    {{ $presensi->sesi->tanggal->format('d/m/Y') }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase
                                        {{ $presensi->status === 'hadir' ? 'bg-secondary-container text-on-secondary-container' :
                                           ($presensi->status === 'terlambat' ? 'bg-amber-100 text-amber-900' :
                                           ($presensi->status === 'izin' ? 'bg-primary-fixed text-on-primary-fixed' :
                                           ($presensi->status === 'sakit' ? 'bg-purple-100 text-purple-900' : 'bg-error-container text-on-error-container'))) }}">
                                        {{ $presensi->status }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-on-surface">
                                    {{ $presensi->waktu_server ? $presensi->waktu_server->format('H:i:s') : '-' }}
                                </td>
                                <td class="py-3.5 px-4 uppercase text-[10px] text-on-surface-variant font-semibold">
                                    {{ $presensi->metode }}
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <button type="button"
                                            @click="selectedRecord = {{ json_encode([
                                                'id' => $presensi->id,
                                                'sesi_id' => $presensi->sesi_id,
                                                'user_id' => $presensi->user_id,
                                                'nama' => $presensi->user->nama ?? '-',
                                                'status' => $presensi->status
                                            ]) }}; showModal = true"
                                            class="px-3 py-1.5 bg-surface-container hover:bg-primary hover:text-on-primary text-primary font-bold text-[11px] rounded-xl transition border border-outline-variant/30 cursor-pointer">
                                        Koreksi
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-on-surface-variant italic">
                                    Tidak ada data presensi yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-outline-variant/20">
                {{ $presensis->links() }}
            </div>
        </div>

        <!-- Modal Koreksi -->
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-xs" @click="showModal = false"></div>
            
            <div class="relative bg-surface-container-lowest rounded-3xl max-w-md w-full p-6 shadow-2xl border border-outline-variant/30 z-10 text-on-surface">
                <div class="flex items-center justify-between pb-3 border-b border-outline-variant/20 mb-4">
                    <h3 class="text-base font-bold text-on-surface">Koreksi Presensi Manual</h3>
                    <button type="button" @click="showModal = false" class="text-on-surface-variant hover:text-on-surface">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                <form method="POST" action="{{ route('tutor.koreksi.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="sesi_id" :value="selectedRecord ? selectedRecord.sesi_id : ''">
                    <input type="hidden" name="user_id" :value="selectedRecord ? selectedRecord.user_id : ''">

                    <div class="p-3 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs">
                        <span class="text-on-surface-variant block">Peserta:</span>
                        <span class="font-bold text-on-surface text-sm" x-text="selectedRecord ? selectedRecord.nama : ''"></span>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-on-surface">Status Baru <span class="text-error">*</span></label>
                        <select name="status_sesudah" required
                                class="w-full px-3 py-2 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs font-bold text-on-surface focus:ring-2 focus:ring-primary focus:outline-none">
                            <option value="hadir">HADIR</option>
                            <option value="terlambat">TERLAMBAT</option>
                            <option value="izin">IZIN</option>
                            <option value="sakit">SAKIT</option>
                            <option value="alpa">ALPA</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-on-surface flex items-center justify-between">
                            <span>Alasan Koreksi</span>
                            <span class="text-error text-[11px] font-semibold">* Wajib Audit</span>
                        </label>
                        <textarea name="alasan" required rows="3" minlength="5"
                                  placeholder="Contoh: Peserta hadir namun kendala koneksi saat memindai QR."
                                  class="w-full p-2.5 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface focus:ring-2 focus:ring-primary focus:outline-none"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-outline-variant/20">
                        <button type="button" @click="showModal = false" class="px-4 py-2 bg-surface-container text-on-surface font-bold text-xs rounded-xl">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-container text-on-primary font-bold text-xs rounded-xl shadow-xs transition">
                            Simpan Koreksi
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
