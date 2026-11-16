<x-app-layout title="Koreksi Presensi">
    <div class="space-y-6" x-data="{ showModal: false, selectedRecord: null }">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-200">
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Koreksi Presensi & Kehadiran</h1>
                <p class="text-xs text-slate-500">Ubah status presensi peserta bermasalah dengan kewajiban mengisi alasan untuk Log Audit.</p>
            </div>
            
            <a href="{{ route('tutor.audit-logs') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-xs rounded-xl transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Lihat Log Audit Lengkap &rarr;
            </a>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
            <form method="GET" action="{{ route('tutor.koreksi.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Pelatihan</label>
                    <select name="pelatihan_id" onchange="this.form.submit()"
                            class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-1 focus:ring-blue-600">
                        <option value="">Semua Pelatihan</option>
                        @foreach($pelatihans as $p)
                            <option value="{{ $p->id }}" {{ ($pelatihanId == $p->id) ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Sesi</label>
                    <select name="sesi_id" onchange="this.form.submit()"
                            class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-1 focus:ring-blue-600">
                        <option value="">Semua Sesi</option>
                        @foreach($sesis as $s)
                            <option value="{{ $s->id }}" {{ ($sesiId == $s->id) ? 'selected' : '' }}>{{ $s->judul_sesi }} ({{ $s->tanggal->format('d/m') }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Status Kehadiran</label>
                    <select name="status" onchange="this.form.submit()"
                            class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-1 focus:ring-blue-600">
                        <option value="">Semua Status</option>
                        <option value="hadir" {{ ($status === 'hadir') ? 'selected' : '' }}>Hadir</option>
                        <option value="terlambat" {{ ($status === 'terlambat') ? 'selected' : '' }}>Terlambat</option>
                        <option value="izin" {{ ($status === 'izin') ? 'selected' : '' }}>Izin</option>
                        <option value="sakit" {{ ($status === 'sakit') ? 'selected' : '' }}>Sakit</option>
                        <option value="alpa" {{ ($status === 'alpa') ? 'selected' : '' }}>Alpa</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Cari Peserta</label>
                    <div class="flex gap-2">
                        <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Nama / No. Induk..."
                               class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-1 focus:ring-blue-600">
                        <button type="submit" class="px-3 py-1.5 bg-slate-800 text-white text-xs font-semibold rounded-xl hover:bg-slate-700 transition">
                            Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Presensi Records Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="text-[11px] text-slate-400 uppercase bg-slate-50/70 border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4">Nama Warga Belajar</th>
                            <th class="py-3 px-4">Sesi & Pelatihan</th>
                            <th class="py-3 px-4">Status Saat Ini</th>
                            <th class="py-3 px-4">Waktu Server</th>
                            <th class="py-3 px-4">Metode</th>
                            <th class="py-3 px-4">Dicatat Oleh</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($presensis as $pr)
                            <tr class="hover:bg-slate-50/60">
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900">{{ $pr->user->nama }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono">{{ $pr->user->nomor_induk ?? '-' }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-semibold text-slate-800">{{ $pr->sesi->judul_sesi }}</div>
                                    <div class="text-[10px] text-slate-500">{{ $pr->sesi->pelatihan->nama }} &bull; {{ $pr->sesi->tanggal->format('d/m/Y') }}</div>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @php
                                        $badges = [
                                            'hadir' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            'terlambat' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'izin' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'sakit' => 'bg-purple-100 text-purple-800 border-purple-200',
                                            'alpa' => 'bg-rose-100 text-rose-800 border-rose-200',
                                        ];
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase border {{ $badges[$pr->status] ?? 'bg-slate-100 text-slate-700' }}">
                                        {{ $pr->status }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-[11px] text-slate-600 whitespace-nowrap">
                                    {{ $pr->waktu_server ? $pr->waktu_server->format('d/m/Y H:i:s') : '-' }}
                                </td>
                                <td class="py-3.5 px-4 uppercase text-[10px] text-slate-500 whitespace-nowrap">
                                    {{ $pr->metode }}
                                </td>
                                <td class="py-3.5 px-4 text-slate-600 text-[11px] whitespace-nowrap">
                                    {{ $pr->dicatatOleh->nama ?? 'Sistem / Mandiri' }}
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <button type="button" @click="selectedRecord = {{ json_encode([
                                        'sesi_id' => $pr->sesi_id,
                                        'user_id' => $pr->user_id,
                                        'nama_peserta' => $pr->user->nama,
                                        'judul_sesi' => $pr->sesi->judul_sesi,
                                        'status' => $pr->status,
                                    ]) }}; showModal = true"
                                            class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-[11px] rounded-lg shadow-xs transition cursor-pointer">
                                        Koreksi Status
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">Tidak ada data presensi yang sesuai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $presensis->links() }}
            </div>
        </div>

        <!-- Modal Koreksi Status (FR-13) -->
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(15, 23, 42, 0.6);">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div @click.away="showModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200">
                    
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                        <h3 class="text-base font-bold text-slate-900">Form Koreksi Presensi Siswa</h3>
                        <button type="button" @click="showModal = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                    </div>

                    <form method="POST" action="{{ route('tutor.koreksi.store') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="sesi_id" :value="selectedRecord ? selectedRecord.sesi_id : ''">
                        <input type="hidden" name="user_id" :value="selectedRecord ? selectedRecord.user_id : ''">

                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-xs space-y-1">
                            <div>Peserta: <strong class="text-slate-900" x-text="selectedRecord ? selectedRecord.nama_peserta : ''"></strong></div>
                            <div>Sesi: <span class="text-slate-700" x-text="selectedRecord ? selectedRecord.judul_sesi : ''"></span></div>
                            <div>Status Saat Ini: <span class="font-bold uppercase text-blue-700" x-text="selectedRecord ? selectedRecord.status : ''"></span></div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Status Baru <span class="text-rose-500">*</span></label>
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
                                Catatan Alasan Koreksi <span class="text-rose-500 font-bold">* (Wajib & Tercatat di Log Audit)</span>
                            </label>
                            <textarea name="alasan" required rows="3" minlength="5"
                                      placeholder="Contoh: Sakit dengan surat dokter terlampir, atau koreksi kehadiran kendala teknis."
                                      class="w-full p-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-hidden"></textarea>
                            <span class="text-[10px] text-slate-400">Minimal 5 karakter agar alasan terdokumentasi jelas.</span>
                        </div>

                        <div class="flex justify-end gap-2 pt-2">
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
</x-app-layout>
