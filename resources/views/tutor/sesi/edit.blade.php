<x-app-layout title="Edit Sesi: {{ $sesi->judul_sesi }}">
    <div class="max-w-2xl mx-auto space-y-6">

        <a href="{{ route('tutor.pelatihan.show', $sesi->pelatihan_id) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
            &larr; Kembali ke Pelatihan: {{ $sesi->pelatihan->nama }}
        </a>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
            <h1 class="text-lg font-bold text-slate-900 mb-1">Edit Sesi Pembelajaran</h1>
            <p class="text-xs text-slate-500 mb-6">Perbarui jadwal tanggal, waktu, atau batas toleransi terlambat.</p>

            <form method="POST" action="{{ route('tutor.sesi.update', $sesi) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="judul_sesi" class="block text-xs font-semibold text-slate-700 mb-1">Judul / Materi Sesi <span class="text-rose-500">*</span></label>
                    <input type="text" id="judul_sesi" name="judul_sesi" value="{{ old('judul_sesi', $sesi->judul_sesi) }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-hidden">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="tanggal" class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Kelas <span class="text-rose-500">*</span></label>
                        <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', $sesi->tanggal->format('Y-m-d')) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600">
                    </div>

                    <div>
                        <label for="jam_mulai" class="block text-xs font-semibold text-slate-700 mb-1">Jam Mulai <span class="text-rose-500">*</span></label>
                        <input type="time" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai', substr((string)$sesi->jam_mulai, 0, 5)) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600">
                    </div>

                    <div>
                        <label for="jam_selesai" class="block text-xs font-semibold text-slate-700 mb-1">Jam Selesai <span class="text-rose-500">*</span></label>
                        <input type="time" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai', substr((string)$sesi->jam_selesai, 0, 5)) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="batas_terlambat_menit" class="block text-xs font-semibold text-slate-700 mb-1">Toleransi Terlambat (Menit) <span class="text-rose-500">*</span></label>
                        <input type="number" id="batas_terlambat_menit" name="batas_terlambat_menit" value="{{ old('batas_terlambat_menit', $sesi->batas_terlambat_menit) }}" min="0" max="180" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600">
                    </div>

                    <div>
                        <label for="lokasi" class="block text-xs font-semibold text-slate-700 mb-1">Lokasi / Ruangan <span class="text-rose-500">*</span></label>
                        <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $sesi->lokasi) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600">
                    </div>
                </div>

                <div class="pt-4 flex justify-between items-center border-t border-slate-100">
                    <button type="button" onclick="if(confirm('Yakin ingin menghapus sesi ini? Seluruh catatan presensi sesi ini akan terhapus.')) { document.getElementById('deleteSesiForm').submit(); }"
                            class="text-xs font-semibold text-rose-600 hover:text-rose-800 cursor-pointer">
                        Hapus Sesi
                    </button>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('tutor.pelatihan.show', $sesi->pelatihan_id) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition cursor-pointer">
                            Perbarui Sesi
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
