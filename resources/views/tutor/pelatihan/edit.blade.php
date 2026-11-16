<x-app-layout title="Edit Pelatihan">
    <div class="max-w-2xl mx-auto space-y-6">

        <a href="{{ route('tutor.pelatihan.show', $pelatihan) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
            &larr; Kembali ke Detail Pelatihan
        </a>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
            <h1 class="text-lg font-bold text-slate-900 mb-1">Edit Program Pelatihan</h1>
            <p class="text-xs text-slate-500 mb-6">Perbarui informasi nama, tutor pengampu, atau pengaturan kuota.</p>

            <form method="POST" action="{{ route('tutor.pelatihan.update', $pelatihan) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="nama" class="block text-xs font-semibold text-slate-700 mb-1">Nama Program Pelatihan <span class="text-rose-500">*</span></label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $pelatihan->nama) }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-hidden">
                </div>

                <div>
                    <label for="tutor_id" class="block text-xs font-semibold text-slate-700 mb-1">Tutor / Instruktur Pengampu <span class="text-rose-500">*</span></label>
                    <select id="tutor_id" name="tutor_id" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600">
                        @foreach($tutors as $tutor)
                            <option value="{{ $tutor->id }}" {{ old('tutor_id', $pelatihan->tutor_id) == $tutor->id ? 'selected' : '' }}>
                                {{ $tutor->nama }} ({{ $tutor->username }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="deskripsi" class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Singkat Program</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3"
                              class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-hidden">{{ old('deskripsi', $pelatihan->deskripsi) }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="kuota" class="block text-xs font-semibold text-slate-700 mb-1">Kuota Peserta</label>
                        <input type="number" id="kuota" name="kuota" value="{{ old('kuota', $pelatihan->kuota) }}" min="1"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-hidden">
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-semibold text-slate-700 mb-1">Status Pelatihan</label>
                        <select id="status" name="status" required
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600">
                            <option value="aktif" {{ old('status', $pelatihan->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="arsip" {{ old('status', $pelatihan->status) === 'arsip' ? 'selected' : '' }}>Arsip</option>
                        </select>
                    </div>
                </div>

                <div class="pt-2">
                    <label class="flex items-start gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-200 cursor-pointer">
                        <input type="checkbox" name="butuh_persetujuan" value="1" {{ old('butuh_persetujuan', $pelatihan->butuh_persetujuan) ? 'checked' : '' }}
                               class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 mt-0.5">
                        <div class="text-xs">
                            <span class="font-bold text-slate-800 block">Wajibkan Persetujuan Tutor</span>
                            <span class="text-slate-500">Warga belajar yang mendaftar harus disetujui manual sebelum terdaftar resmi.</span>
                        </div>
                    </label>
                </div>

                <div class="pt-4 flex justify-between items-center border-t border-slate-100">
                    <button type="button" onclick="if(confirm('Yakin ingin menghapus pelatihan ini beserta seluruh sesi dan presensi terkait?')) { document.getElementById('deletePelatihanForm').submit(); }"
                            class="text-xs font-semibold text-rose-600 hover:text-rose-800 cursor-pointer">
                        Hapus Pelatihan
                    </button>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('tutor.pelatihan.show', $pelatihan) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition cursor-pointer">
                            Perbarui Pelatihan
                        </button>
                    </div>
                </div>
            </form>

            <form id="deletePelatihanForm" method="POST" action="{{ route('tutor.pelatihan.destroy', $pelatihan) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>

    </div>
</x-app-layout>
