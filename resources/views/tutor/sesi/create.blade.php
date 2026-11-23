<x-app-layout title="Tambah Sesi Baru">
    <div class="max-w-2xl mx-auto flex flex-col gap-6">

        @if($pelatihan && $pelatihan->exists)
            <a href="{{ route('tutor.pelatihan.show', $pelatihan) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:underline">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                <span>Kembali ke Pelatihan: {{ $pelatihan->nama }}</span>
            </a>
        @else
            <a href="{{ route('tutor.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:underline">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                <span>Kembali ke Dashboard Utama</span>
            </a>
        @endif

        <div class="bg-surface-container-lowest rounded-3xl border border-outline-variant/30 shadow-xs p-6 sm:p-8">
            <div class="flex items-center gap-3 pb-4 border-b border-outline-variant/20 mb-6">
                <div class="w-10 h-10 rounded-2xl bg-primary-fixed text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">add_circle</span>
                </div>
                <div>
                    <h1 class="text-lg sm:text-xl font-extrabold text-on-surface">Tambah Sesi Pembelajaran Baru</h1>
                    <p class="text-xs text-on-surface-variant">Jadwalkan pertemuan kelas, jam presensi, dan lokasi ruangan belajar.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('tutor.sesi.store') }}" class="space-y-4">
                @csrf

                <div class="flex flex-col gap-1.5">
                    <label for="pelatihan_id" class="text-xs font-bold text-on-surface flex items-center justify-between">
                        <span>Program Pelatihan</span>
                        <span class="text-error text-[11px] font-semibold">* Wajib</span>
                    </label>
                    <select id="pelatihan_id" name="pelatihan_id" required
                            class="w-full px-3.5 py-2.5 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface font-semibold focus:ring-2 focus:ring-primary focus:outline-none">
                        @if(isset($pelatihans) && $pelatihans->isNotEmpty())
                            @foreach($pelatihans as $p)
                                <option value="{{ $p->id }}" {{ old('pelatihan_id', $pelatihan?->id) == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }} ({{ $p->kategori }})
                                </option>
                            @endforeach
                        @elseif($pelatihan)
                            <option value="{{ $pelatihan->id }}" selected>{{ $pelatihan->nama }}</option>
                        @endif
                    </select>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label for="judul_sesi" class="text-xs font-bold text-on-surface flex items-center justify-between">
                        <span>Judul / Materi Sesi</span>
                        <span class="text-error text-[11px] font-semibold">* Wajib</span>
                    </label>
                    <input type="text" id="judul_sesi" name="judul_sesi" value="{{ old('judul_sesi') }}" required
                           class="w-full px-3.5 py-2.5 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary focus:outline-none"
                           placeholder="Contoh: Modul 1 — Pengantar Desain Grafis & Vektor">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label for="tanggal" class="text-xs font-bold text-on-surface">Tanggal Kelas <span class="text-error">*</span></label>
                        <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}" required
                               class="w-full px-3.5 py-2.5 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface font-semibold focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="jam_mulai" class="text-xs font-bold text-on-surface">Jam Mulai <span class="text-error">*</span></label>
                        <input type="time" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai', now()->subMinutes(5)->format('H:i')) }}" required
                               class="w-full px-3.5 py-2.5 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface font-semibold focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="jam_selesai" class="text-xs font-bold text-on-surface">Jam Selesai <span class="text-error">*</span></label>
                        <input type="time" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai', now()->addHours(2)->format('H:i')) }}" required
                               class="w-full px-3.5 py-2.5 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface font-semibold focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label for="batas_terlambat_menit" class="text-xs font-bold text-on-surface">Toleransi Terlambat (Menit) <span class="text-error">*</span></label>
                        <input type="number" id="batas_terlambat_menit" name="batas_terlambat_menit" value="{{ old('batas_terlambat_menit', 15) }}" min="0" max="180" required
                               class="w-full px-3.5 py-2.5 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface font-semibold focus:ring-2 focus:ring-primary focus:outline-none">
                        <span class="text-[11px] text-on-surface-variant">Setelah (Jam Mulai + Toleransi) otomatis berstatus Terlambat.</span>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="lokasi" class="text-xs font-bold text-on-surface">Lokasi / Ruangan <span class="text-error">*</span></label>
                        <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', 'Lab Komputer 1 PKBM Karawang') }}" required
                               class="w-full px-3.5 py-2.5 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary focus:outline-none"
                               placeholder="Contoh: Lab Komputer 1">
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-surface-container-low border border-outline-variant/30 text-xs text-on-surface space-y-1.5">
                    <div class="font-bold text-primary flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px]">info</span>
                        <span>Ketentuan Jendela Waktu Presensi:</span>
                    </div>
                    <ul class="list-disc list-inside text-[11px] text-on-surface-variant space-y-0.5">
                        <li>Presensi dibuka otomatis mulai 15 menit sebelum jam mulai hingga jam selesai sesi.</li>
                        <li>Token QR layar penuh akan otomatis diperbarui setiap 5 menit dengan toleransi jeda 15 detik.</li>
                        <li>Peserta dapat scan langsung dari kamera HP atau memasukkan kode manual 6-digit.</li>
                    </ul>
                </div>

                <div class="pt-4 flex justify-end gap-2.5 border-t border-outline-variant/20">
                    <a href="{{ $pelatihan && $pelatihan->exists ? route('tutor.pelatihan.show', $pelatihan) : route('tutor.dashboard') }}"
                       class="px-4 py-2.5 bg-surface-container hover:bg-surface-container-high text-on-surface font-bold text-xs rounded-xl transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-5 py-2.5 bg-primary hover:bg-primary-container text-on-primary font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        <span>Simpan & Buat Sesi</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
