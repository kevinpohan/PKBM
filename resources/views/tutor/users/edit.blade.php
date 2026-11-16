<x-app-layout title="Edit Pengguna: {{ $user->nama }}">
    <div class="max-w-2xl mx-auto space-y-6">

        <a href="{{ route('tutor.users.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
            &larr; Kembali ke Daftar Pengguna
        </a>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
            <h1 class="text-lg font-bold text-slate-900 mb-1">Edit Data Pengguna</h1>
            <p class="text-xs text-slate-500 mb-6">Perbarui informasi identitas, nomor telepon, atau status keaktifan.</p>

            <form method="POST" action="{{ route('tutor.users.update', $user) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nama" class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-hidden">
                    </div>

                    <div>
                        <label for="username" class="block text-xs font-semibold text-slate-700 mb-1">Username (Unik) <span class="text-rose-500">*</span></label>
                        <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-hidden">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="role" class="block text-xs font-semibold text-slate-700 mb-1">Peran / Role <span class="text-rose-500">*</span></label>
                        <select id="role" name="role" required
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600">
                            <option value="peserta" {{ old('role', $user->role) === 'peserta' ? 'selected' : '' }}>Warga Belajar (Peserta)</option>
                            <option value="tutor" {{ old('role', $user->role) === 'tutor' ? 'selected' : '' }}>Tutor / Instruktur</option>
                        </select>
                    </div>

                    <div>
                        <label for="nomor_induk" class="block text-xs font-semibold text-slate-700 mb-1">Nomor Induk</label>
                        <input type="text" id="nomor_induk" name="nomor_induk" value="{{ old('nomor_induk', $user->nomor_induk) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-hidden">
                    </div>

                    <div>
                        <label for="no_hp" class="block text-xs font-semibold text-slate-700 mb-1">No. HP / WhatsApp</label>
                        <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-hidden">
                    </div>
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                               class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-xs font-semibold text-slate-700">Akun Aktif (Dapat Login)</span>
                    </label>
                </div>

                <div class="pt-4 flex justify-between items-center border-t border-slate-100">
                    @if(auth()->id() !== $user->id)
                        <button type="button" onclick="if(confirm('Yakin ingin menghapus akun ini?')) { document.getElementById('deleteUserForm').submit(); }"
                                class="text-xs font-semibold text-rose-600 hover:text-rose-800 cursor-pointer">
                            Hapus Akun
                        </button>
                    @else
                        <div></div>
                    @endif

                    <div class="flex items-center gap-2">
                        <a href="{{ route('tutor.users.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition cursor-pointer">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>

            @if(auth()->id() !== $user->id)
                <form id="deleteUserForm" method="POST" action="{{ route('tutor.users.destroy', $user) }}" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            @endif
        </div>

    </div>
</x-app-layout>
