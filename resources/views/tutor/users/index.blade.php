<x-app-layout title="Manajemen Pengguna">
    <div class="flex flex-col gap-6" x-data="{ showImportModal: false, showResetModal: false, resetTargetUser: null }">

        <!-- Top Header Bar -->
        <div class="bg-surface-container-lowest p-5 sm:p-6 rounded-2xl shadow-xs border border-outline-variant/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-primary"></span>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-on-surface tracking-tight">Manajemen Pengguna & Akun</h1>
                </div>
                <p class="text-xs text-on-surface-variant mt-1">Kelola data warga belajar (peserta) dan instruktur/tutor PKBM Karawang.</p>
            </div>
            
            <div class="flex items-center gap-2.5 flex-wrap self-start sm:self-auto">
                <a href="{{ route('tutor.users.template') }}"
                   class="px-3.5 py-2.5 bg-surface-container hover:bg-surface-container-high text-on-surface font-bold text-xs rounded-xl border border-outline-variant/30 transition inline-flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">download</span>
                    <span>Format CSV</span>
                </a>
                <button type="button" @click="showImportModal = true"
                        class="px-3.5 py-2.5 bg-secondary hover:bg-secondary/90 text-on-secondary font-bold text-xs rounded-xl shadow-xs transition inline-flex items-center gap-1.5 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">upload_file</span>
                    <span>Impor CSV</span>
                </button>
                <a href="{{ route('tutor.users.create') }}"
                   class="px-4 py-2.5 bg-primary hover:bg-primary-container text-on-primary font-bold text-xs rounded-xl shadow-xs transition inline-flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">person_add</span>
                    <span>Tambah Akun</span>
                </a>
            </div>
        </div>

        @if(session('import_errors') && count(session('import_errors')) > 0)
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-900 shadow-xs">
                <span class="font-bold block mb-1">Catatan Hasil Impor CSV:</span>
                <ul class="list-disc list-inside space-y-0.5 text-[11px]">
                    @foreach(session('import_errors') as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Filter Bar -->
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 p-4 sm:p-5 shadow-xs">
            <form method="GET" action="{{ route('tutor.users.index') }}" class="flex flex-wrap items-center gap-3">
                <div class="relative flex-1 min-w-[200px]">
                    <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari nama, username, no. induk..."
                           class="w-full pl-9 pr-3 py-2 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary focus:outline-none">
                    <span class="material-symbols-outlined absolute left-2.5 top-2 text-[18px] text-outline pointer-events-none">search</span>
                </div>

                <select name="role" class="px-3 py-2 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface font-semibold focus:ring-2 focus:ring-primary focus:outline-none">
                    <option value="">Semua Peran</option>
                    <option value="peserta" {{ ($role ?? '') === 'peserta' ? 'selected' : '' }}>Warga Belajar (Peserta)</option>
                    <option value="tutor" {{ ($role ?? '') === 'tutor' ? 'selected' : '' }}>Tutor / Instruktur</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-container text-on-primary font-bold text-xs rounded-xl shadow-xs transition">
                    Filter
                </button>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-on-surface">
                    <thead class="text-[11px] text-on-surface-variant uppercase bg-surface-container-low border-b border-outline-variant/20">
                        <tr>
                            <th class="py-3 px-4">Nama Lengkap</th>
                            <th class="py-3 px-4">Username</th>
                            <th class="py-3 px-4">Peran (Role)</th>
                            <th class="py-3 px-4">Nomor Induk</th>
                            <th class="py-3 px-4">No. HP</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/15">
                        @forelse($users as $u)
                            <tr class="hover:bg-surface-container-low/50 transition">
                                <td class="py-3.5 px-4 font-bold text-on-surface">
                                    {{ $u->nama }}
                                </td>
                                <td class="py-3.5 px-4 font-mono text-[11px] text-on-surface-variant">
                                    {{ $u->username }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $u->isTutor() ? 'bg-primary-fixed text-on-primary-fixed' : 'bg-secondary-container text-on-secondary-container' }}">
                                        {{ $u->role }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-[11px] text-on-surface-variant">
                                    {{ $u->nomor_induk ?? '-' }}
                                </td>
                                <td class="py-3.5 px-4 text-on-surface-variant">
                                    {{ $u->no_hp ?? '-' }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $u->is_active ? 'bg-secondary-container text-on-secondary-container' : 'bg-error-container text-on-error-container' }}">
                                        {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap space-x-1">
                                    <button type="button" @click="resetTargetUser = {{ json_encode($u) }}; showResetModal = true"
                                            class="px-2.5 py-1.5 bg-surface-container hover:bg-amber-100 text-amber-900 text-[11px] font-bold rounded-lg border border-outline-variant/30 transition cursor-pointer">
                                        Reset Sandi
                                    </button>
                                    <a href="{{ route('tutor.users.edit', $u) }}"
                                       class="px-2.5 py-1.5 bg-surface-container hover:bg-surface-container-high text-on-surface text-[11px] font-bold rounded-lg border border-outline-variant/30 transition">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-on-surface-variant italic">Belum ada pengguna yang terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-outline-variant/20">
                {{ $users->links() }}
            </div>
        </div>

        <!-- Modal Impor CSV -->
        <div x-show="showImportModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-xs" @click="showImportModal = false"></div>
            
            <div class="relative bg-surface-container-lowest rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-outline-variant/30 z-10 text-on-surface">
                <div class="flex items-center justify-between pb-3 border-b border-outline-variant/20 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-[22px]">upload_file</span>
                        <h3 class="text-base font-bold text-on-surface">Impor Massal Akun via CSV</h3>
                    </div>
                    <button type="button" @click="showImportModal = false" class="p-1 rounded-lg text-on-surface-variant hover:bg-surface-container">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                <form method="POST" action="{{ route('tutor.users.import') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-on-surface mb-1.5">Pilih File CSV:</label>
                        <input type="file" name="file" accept=".csv,.txt" required
                               class="w-full text-xs text-on-surface file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary file:text-on-primary hover:file:bg-primary-container">
                        <span class="text-[11px] text-on-surface-variant mt-1.5 block leading-relaxed">
                            Pastikan format kolom sesuai dengan template (nama, username, password, role, nomor_induk, no_hp).
                        </span>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-outline-variant/20">
                        <button type="button" @click="showImportModal = false"
                                class="px-4 py-2 bg-surface-container text-on-surface font-bold text-xs rounded-xl">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-secondary hover:bg-secondary/90 text-on-secondary font-bold text-xs rounded-xl shadow-xs transition">
                            Proses Impor
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Reset Password -->
        <div x-show="showResetModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-xs" @click="showResetModal = false"></div>
            
            <div class="relative bg-surface-container-lowest rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-outline-variant/30 z-10 text-on-surface">
                <div class="flex items-center justify-between pb-3 border-b border-outline-variant/20 mb-4">
                    <h3 class="text-base font-bold text-on-surface">Reset Kata Sandi Akun</h3>
                    <button type="button" @click="showResetModal = false" class="text-on-surface-variant hover:text-on-surface">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                <form method="POST" :action="resetTargetUser ? `/tutor/users/${resetTargetUser.id}/reset-password` : '#'" class="space-y-4">
                    @csrf
                    <div>
                        <span class="text-[11px] text-on-surface-variant block">Reset sandi untuk:</span>
                        <span class="text-sm font-bold text-on-surface" x-text="resetTargetUser ? resetTargetUser.nama : ''"></span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-on-surface mb-1">Kata Sandi Baru <span class="text-error">*</span></label>
                        <input type="password" name="password" minlength="6" required placeholder="Minimal 6 karakter"
                               class="w-full px-3 py-2 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-on-surface mb-1">Konfirmasi Sandi Baru <span class="text-error">*</span></label>
                        <input type="password" name="password_confirmation" minlength="6" required placeholder="Ulangi sandi baru"
                               class="w-full px-3 py-2 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs text-on-surface focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-outline-variant/20">
                        <button type="button" @click="showResetModal = false" class="px-4 py-2 bg-surface-container text-on-surface font-bold text-xs rounded-xl">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow-xs transition">
                            Reset Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
