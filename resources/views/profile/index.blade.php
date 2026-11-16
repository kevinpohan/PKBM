<x-app-layout title="Profil & Ganti Kata Sandi">
    <div class="max-w-3xl mx-auto space-y-6">

        <div class="flex items-center justify-between pb-4 border-b border-slate-200">
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Profil Pengguna & Keamanan</h1>
                <p class="text-xs text-slate-500">Kelola identitas akun dan perbarui kata sandi Anda.</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $user->isTutor() ? 'bg-indigo-100 text-indigo-800' : 'bg-emerald-100 text-emerald-800' }}">
                {{ $user->isTutor() ? 'Akun Tutor / Instruktur' : 'Akun Warga Belajar' }}
            </span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                @csrf

                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nama" class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-hidden transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Username (Login)</label>
                        <input type="text" disabled value="{{ $user->username }}"
                               class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm text-slate-500 cursor-not-allowed">
                        <span class="text-[10px] text-slate-400 mt-0.5 block">Username unik tidak dapat diubah sendiri.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Induk (NISN/NIK/NIP)</label>
                        <input type="text" disabled value="{{ $user->nomor_induk ?? '-' }}"
                               class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm text-slate-500 cursor-not-allowed">
                    </div>

                    <div>
                        <label for="no_hp" class="block text-xs font-semibold text-slate-700 mb-1">Nomor Telepon / WhatsApp</label>
                        <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-hidden transition"
                               placeholder="Contoh: 081234567890">
                    </div>
                </div>

                <!-- Password Change Section -->
                <div class="pt-6 border-t border-slate-100">
                    <h2 class="text-sm font-bold text-slate-900 mb-1">Ganti Kata Sandi (Opsional)</h2>
                    <p class="text-xs text-slate-500 mb-4">Kosongkan kolom di bawah jika Anda tidak ingin mengubah kata sandi.</p>

                    <div class="space-y-4 max-w-md">
                        <div>
                            <label for="password_lama" class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi Saat Ini</label>
                            <input type="password" id="password_lama" name="password_lama"
                                   class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-hidden transition"
                                   placeholder="••••••••">
                        </div>

                        <div>
                            <label for="password_baru" class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi Baru</label>
                            <input type="password" id="password_baru" name="password_baru"
                                   class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-hidden transition"
                                   placeholder="Minimal 6 karakter">
                        </div>

                        <div>
                            <label for="password_baru_confirmation" class="block text-xs font-semibold text-slate-700 mb-1">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" id="password_baru_confirmation" name="password_baru_confirmation"
                                   class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-hidden transition"
                                   placeholder="Ulangi kata sandi baru">
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit"
                            class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl shadow-sm transition cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
