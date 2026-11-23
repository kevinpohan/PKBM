<x-app-layout title="Profil & Ganti Kata Sandi">
    <div class="max-w-3xl mx-auto flex flex-col gap-6">

        <div class="bg-surface-container-lowest p-5 sm:p-6 rounded-2xl shadow-xs border border-outline-variant/30 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-primary"></span>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-on-surface tracking-tight">Profil Akun & Keamanan</h1>
                </div>
                <p class="text-xs text-on-surface-variant mt-1">Kelola data profil pengguna dan perbarui kata sandi akun Anda.</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $user->isTutor() ? 'bg-primary-fixed text-on-primary-fixed' : 'bg-secondary-container text-on-secondary-container' }} self-start sm:self-auto">
                <span class="material-symbols-outlined text-[16px]">{{ $user->isTutor() ? 'badge' : 'school' }}</span>
                <span>{{ $user->isTutor() ? 'Tutor / Instruktur' : 'Warga Belajar' }}</span>
            </span>
        </div>

        <div class="bg-surface-container-lowest rounded-3xl border border-outline-variant/30 shadow-xs p-6 sm:p-8">
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                @csrf

                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label for="nama" class="text-xs font-bold text-on-surface">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required
                               class="w-full px-3.5 py-2.5 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface focus:ring-2 focus:ring-primary focus:outline-none transition">
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-on-surface">Username (Login)</label>
                        <input type="text" disabled value="{{ $user->username }}"
                               class="w-full px-3.5 py-2.5 bg-surface-container border border-outline-variant/20 rounded-xl text-xs text-on-surface-variant cursor-not-allowed">
                        <span class="text-[10px] text-on-surface-variant">Username terdaftar di sistem Dapodik/PKBM.</span>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-on-surface">Nomor Induk (NISN/NIK/NUPTK)</label>
                        <input type="text" disabled value="{{ $user->nomor_induk ?? '-' }}"
                               class="w-full px-3.5 py-2.5 bg-surface-container border border-outline-variant/20 rounded-xl text-xs text-on-surface-variant cursor-not-allowed">
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="no_hp" class="text-xs font-bold text-on-surface">Nomor WhatsApp / HP</label>
                        <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                               class="w-full px-3.5 py-2.5 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface focus:ring-2 focus:ring-primary focus:outline-none transition"
                               placeholder="Contoh: 081234567890">
                    </div>
                </div>

                <!-- Password Change Section -->
                <div class="pt-6 border-t border-outline-variant/20">
                    <h2 class="text-sm font-bold text-on-surface mb-1">Ganti Kata Sandi (Opsional)</h2>
                    <p class="text-xs text-on-surface-variant mb-4">Kosongkan jika Anda tidak ingin memperbarui kata sandi.</p>

                    <div class="space-y-4 max-w-md">
                        <div class="flex flex-col gap-1.5">
                            <label for="password_lama" class="text-xs font-bold text-on-surface">Kata Sandi Saat Ini</label>
                            <input type="password" id="password_lama" name="password_lama"
                                   class="w-full px-3.5 py-2 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface focus:ring-2 focus:ring-primary focus:outline-none transition"
                                   placeholder="••••••••">
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label for="password_baru" class="text-xs font-bold text-on-surface">Kata Sandi Baru</label>
                            <input type="password" id="password_baru" name="password_baru"
                                   class="w-full px-3.5 py-2 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface focus:ring-2 focus:ring-primary focus:outline-none transition"
                                   placeholder="Minimal 6 karakter">
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label for="password_baru_confirmation" class="text-xs font-bold text-on-surface">Konfirmasi Sandi Baru</label>
                            <input type="password" id="password_baru_confirmation" name="password_baru_confirmation"
                                   class="w-full px-3.5 py-2 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface focus:ring-2 focus:ring-primary focus:outline-none transition"
                                   placeholder="Ulangi kata sandi baru">
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex justify-end border-t border-outline-variant/20">
                    <button type="submit"
                            class="px-5 py-2.5 bg-primary hover:bg-primary-container text-on-primary font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
                        Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
