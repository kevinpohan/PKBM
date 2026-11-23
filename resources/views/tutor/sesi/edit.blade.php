<x-app-layout title="Edit Sesi: {{ $sesi->judul_sesi }}">
    <div class="max-w-2xl mx-auto flex flex-col gap-6">

        <a href="{{ route('tutor.pelatihan.show', $sesi->pelatihan_id) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:underline">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            <span>Kembali ke Pelatihan: {{ $sesi->pelatihan->nama }}</span>
        </a>

        @if ($errors->any())
            <div class="p-4 rounded-2xl bg-error-container text-on-error-container text-xs">
                <div class="font-bold mb-1 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">error</span>
                    <span>Terdapat kendala pada input:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-surface-container-lowest rounded-3xl border border-outline-variant/30 shadow-xs p-6 sm:p-8">
            <div class="flex items-center gap-3 pb-4 border-b border-outline-variant/20 mb-6">
                <div class="w-10 h-10 rounded-2xl bg-primary-fixed text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">edit_calendar</span>
                </div>
                <div>
                    <h1 class="text-lg sm:text-xl font-extrabold text-on-surface">Edit Sesi Pembelajaran</h1>
                    <p class="text-xs text-on-surface-variant">Perbarui materi, jadwal tanggal & waktu, atau batas toleransi terlambat.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('tutor.sesi.update', $sesi) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="flex flex-col gap-1.5">
                    <label for="pelatihan_id" class="text-xs font-bold text-on-surface flex items-center justify-between">
                        <span>Program Pelatihan</span>
                        <span class="text-error text-[11px] font-semibold">* Wajib</span>
                    </label>
                    <select id="pelatihan_id" name="pelatihan_id" required
                            class="w-full px-3.5 py-2.5 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface font-semibold focus:ring-2 focus:ring-primary focus:outline-none">
                        @foreach($pelatihans as $p)
                            <option value="{{ $p->id }}" {{ (string) old('pelatihan_id', $sesi->pelatihan_id) === (string) $p->id ? 'selected' : '' }}>
                                {{ $p->nama }} ({{ $p->kategori }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label for="judul_sesi" class="text-xs font-bold text-on-surface flex items-center justify-between">
                        <span>Judul / Materi Sesi</span>
                        <span class="text-error text-[11px] font-semibold">* Wajib</span>
                    </label>
                    <input type="text" id="judul_sesi" name="judul_sesi" value="{{ old('judul_sesi', $sesi->judul_sesi) }}" required
                           class="w-full px-3.5 py-2.5 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary focus:outline-none"
                           placeholder="Contoh: Pertemuan 1 — Pengenalan Antarmuka">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label for="tanggal" class="text-xs font-bold text-on-surface">Tanggal Kelas <span class="text-error">*</span></label>
                        <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', $sesi->tanggal->format('Y-m-d')) }}" required
                               class="w-full px-3.5 py-2.5 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface font-semibold focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="jam_mulai" class="text-xs font-bold text-on-surface">Jam Mulai <span class="text-error">*</span></label>
                        <input type="time" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai', substr((string)$sesi->jam_mulai, 0, 5)) }}" required
                               class="w-full px-3.5 py-2.5 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface font-semibold focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="jam_selesai" class="text-xs font-bold text-on-surface">Jam Selesai <span class="text-error">*</span></label>
                        <input type="time" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai', substr((string)$sesi->jam_selesai, 0, 5)) }}" required
                               class="w-full px-3.5 py-2.5 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface font-semibold focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label for="batas_terlambat_menit" class="text-xs font-bold text-on-surface">Toleransi Terlambat (Menit) <span class="text-error">*</span></label>
                        <input type="number" id="batas_terlambat_menit" name="batas_terlambat_menit" value="{{ old('batas_terlambat_menit', $sesi->batas_terlambat_menit) }}" min="0" max="180" required
                               class="w-full px-3.5 py-2.5 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface font-semibold focus:ring-2 focus:ring-primary focus:outline-none">
                        <span class="text-[11px] text-on-surface-variant">Setelah (Jam Mulai + Toleransi) otomatis berstatus Terlambat.</span>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="lokasi" class="text-xs font-bold text-on-surface">Lokasi / Ruangan <span class="text-error">*</span></label>
                        <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $sesi->lokasi) }}" required
                               class="w-full px-3.5 py-2.5 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>
                </div>

                <div class="pt-4 flex justify-between items-center border-t border-outline-variant/20 flex-wrap gap-3">
                    <button type="button" onclick="if(confirm('Yakin ingin menghapus sesi ini? Seluruh catatan presensi sesi ini akan terhapus.')) { document.getElementById('deleteSesiForm').submit(); }"
                            class="px-4 py-2.5 rounded-xl text-xs font-bold text-error hover:bg-error-container/30 transition cursor-pointer flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                        <span>Hapus Sesi</span>
                    </button>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('tutor.pelatihan.show', $sesi->pelatihan_id) }}" class="px-4 py-2.5 bg-surface-container hover:bg-surface-container-high text-on-surface font-bold text-xs rounded-xl transition">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2.5 bg-primary hover:bg-primary-container text-on-primary font-bold text-xs rounded-xl shadow-xs transition cursor-pointer flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </div>
            </form>

            <form id="deleteSesiForm" method="POST" action="{{ route('tutor.sesi.destroy', $sesi) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>

    </div>
</x-app-layout>
