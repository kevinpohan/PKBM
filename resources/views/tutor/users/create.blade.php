<x-app-layout title="Tambah Pengguna Baru">
    <div class="max-w-2xl mx-auto space-y-6">

        <a href="{{ route('tutor.users.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
            &larr; Kembali ke Daftar Pengguna
        </a>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
            <h1 class="text-lg font-bold text-slate-900 mb-1">Tambah Akun Pengguna Baru</h1>
            <p class="text-xs text-slate-500 mb-6">Daftarkan akun Warga Belajar atau Tutor ke dalam sistem.</p>

            <form method="POST" action="{{ route('tutor.users.store') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nama" class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-hidden"
                               placeholder="Contoh: Rian Hidayat">
                    </div>

                    <div>
                        <label for="username" class="block text-xs font-semibold text-slate-700 mb-1">Username (Unik) <span class="text-rose-500">*</span></label>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-hidden"
                               placeholder="Contoh: rian_hidayat">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="role" class="block text-xs font-semibold text-slate-700 mb-1">Peran / Role <span class="text-rose-500">*</span></label>
                        <select id="role" name="role" required
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600">
                            <option value="peserta" {{ old('role', 'peserta') === 'peserta' ? 'selected' : '' }}>Warga Belajar (Peserta)</option>
                            <option value="tutor" {{ old('role') === 'tutor' ? 'selected' : '' }}>Tutor / Instruktur</option>
                        </select>
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi Awal</label>
                        <input type="text" id="password" name="password" value="{{ old('password') }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-hidden"
                               placeholder="Kosongkan jika default: pkbm12345">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nomor_induk" class="block text-xs font-semibold text-slate-700 mb-1">Nomor Induk (NISN/NIK/NIP)</label>
                        <input type="text" id="nomor_induk" name="nomor_induk" value="{{ old('nomor_induk') }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-hidden"
                               placeholder="Contoh: WB-2026-015">
                    </div>

                    <div>
                        <label for="no_hp" class="block text-xs font-semibold text-slate-700 mb-1">Nomor WhatsApp / HP</label>
                        <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-hidden"
                               placeholder="Contoh: 08123456789">
                    </div>
                </div>

                <div>
                    <label for="pelatihan_id" class="block text-xs font-semibold text-slate-700 mb-1">Daftarkan Langsung ke Pelatihan (Khusus Peserta)</label>
                    <select id="pelatihan_id" name="pelatihan_id"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600">
                        <option value="">-- Tidak Mendaftar Langsung --</option>
                        @foreach($pelatihans as $p)
                            <option value="{{ $p->id }}" {{ old('pelatihan_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }} (Tutor: {{ $p->tutor->nama ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-xs font-semibold text-slate-700">Akun Aktif (Dapat Login)</span>
                    </label>
                </div>

                <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                    <a href="{{ route('tutor.users.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition cursor-pointer">
                        Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
