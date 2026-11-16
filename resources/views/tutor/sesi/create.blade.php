<x-app-layout title="Tambah Sesi Baru">
    <div class="max-w-2xl mx-auto space-y-6">

        <a href="{{ route('tutor.pelatihan.show', $pelatihan) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
            &larr; Kembali ke Pelatihan: {{ $pelatihan->nama }}
        </a>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
            <h1 class="text-lg font-bold text-slate-900 mb-1">Tambah Sesi Pembelajaran Baru</h1>
            <p class="text-xs text-slate-500 mb-6">Jadwalkan pertemuan kelas, jam presensi, dan lokasi ruangan.</p>

            <form method="POST" action="{{ route('tutor.pelatihan.sesi.store', $pelatihan) }}" class="space-y-4">
                @csrf

                <div>
                    <label for="judul_sesi" class="block text-xs font-semibold text-slate-700 mb-1">Judul / Materi Sesi <span class="text-rose-500">*</span></label>
                    <input type="text" id="judul_sesi" name="judul_sesi" value="{{ old('judul_sesi') }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-hidden"
                           placeholder="Contoh: Modul 3 — Pembagian Sel Mitosis & Meiosis">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="tanggal" class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Kelas <span class="text-rose-500">*</span></label>
                        <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600">
                    </div>

                    <div>
                        <label for="jam_mulai" class="block text-xs font-semibold text-slate-700 mb-1">Jam Mulai <span class="text-rose-500">*</span></label>
                        <input type="time" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai', '08:00') }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600">
                    </div>

                    <div>
                        <label for="jam_selesai" class="block text-xs font-semibold text-slate-700 mb-1">Jam Selesai <span class="text-rose-500">*</span></label>
                        <input type="time" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai', '10:00') }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="batas_terlambat_menit" class="block text-xs font-semibold text-slate-700 mb-1">Toleransi Terlambat (Menit) <span class="text-rose-500">*</span></label>
                        <input type="number" id="batas_terlambat_menit" name="batas_terlambat_menit" value="{{ old('batas_terlambat_menit', 15) }}" min="0" max="180" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600">
                        <span class="text-[10px] text-slate-400 mt-1 block">Presensi setelah (Jam Mulai + Toleransi) otomatis berstatus Terlambat.</span>
                    </div>

                    <div>
                        <label for="lokasi" class="block text-xs font-semibold text-slate-700 mb-1">Lokasi / Ruangan <span class="text-rose-500">*</span></label>
                        <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', 'Ruang Kelas 1 PKBM') }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600"
                               placeholder="Contoh: Lab Komputer Lt. 2">
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-blue-50 border border-blue-200 text-xs text-blue-800 space-y-1">
                    <div class="font-bold">Ketentuan Jendela Waktu Presensi (FR-08, FR-11):</div>
                    <ul class="list-disc list-inside text-[11px] text-blue-700 space-y-0.5">
                        <li>Presensi dibuka otomatis 15 menit sebelum jam mulai hingga jam selesai sesi.</li>
                        <li>Token QR layar penuh akan otomatis berganti setiap 5 menit dengan masa tenggang jeda 15 detik.</li>
                    </ul>
                </div>

                <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                    <a href="{{ route('tutor.pelatihan.show', $pelatihan) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition cursor-pointer">
                        Simpan Sesi
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
