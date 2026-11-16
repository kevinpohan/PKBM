<x-app-layout title="Manajemen Pengguna">
    <div class="space-y-6" x-data="{ showImportModal: false, showResetModal: false, resetTargetUser: null }">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-200">
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Manajemen Pengguna & Akun</h1>
                <p class="text-xs text-slate-500">Kelola akun Warga Belajar (Peserta) dan Tutor / Instruktur PKBM.</p>
            </div>
            
            <div class="flex items-center gap-2">
                <a href="{{ route('tutor.users.template') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    Template CSV
                </a>
                <button type="button" @click="showImportModal = true" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-xs transition inline-flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                    Impor CSV
                </button>
                <a href="{{ route('tutor.users.create') }}" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                    Tambah Akun
                </a>
            </div>
        </div>

        @if(session('import_errors') && count(session('import_errors')) > 0)
            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-800">
                <span class="font-bold block mb-1">Catatan Hasil Impor:</span>
                <ul class="list-disc list-inside space-y-0.5 text-[11px]">
                    @foreach(session('import_errors') as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Filter Bar -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <form method="GET" action="{{ route('tutor.users.index') }}" class="flex flex-wrap items-center gap-2">
                <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari nama, username, no. induk..."
                       class="px-3 py-1.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-1 focus:ring-blue-600 w-60">

                <select name="role" class="px-3 py-1.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-1 focus:ring-blue-600">
                    <option value="">Semua Peran</option>
                    <option value="peserta" {{ ($role ?? '') === 'peserta' ? 'selected' : '' }}>Warga Belajar (Peserta)</option>
                    <option value="tutor" {{ ($role ?? '') === 'tutor' ? 'selected' : '' }}>Tutor / Instruktur</option>
                </select>

                <button type="submit" class="px-3 py-1.5 bg-slate-800 text-white text-xs font-semibold rounded-xl hover:bg-slate-700 transition">
                    Filter
                </button>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="text-[11px] text-slate-400 uppercase bg-slate-50/70 border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4">Nama Lengkap</th>
                            <th class="py-3 px-4">Username</th>
                            <th class="py-3 px-4">Peran (Role)</th>
                            <th class="py-3 px-4">Nomor Induk</th>
                            <th class="py-3 px-4">No. HP</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $u)
                            <tr class="hover:bg-slate-50/60">
                                <td class="py-3 px-4 font-bold text-slate-900">
                                    {{ $u->nama }}
                                </td>
                                <td class="py-3 px-4 font-mono text-[11px] text-slate-600">
                                    {{ $u->username }}
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $u->isTutor() ? 'bg-indigo-100 text-indigo-800' : 'bg-emerald-100 text-emerald-800' }}">
                                        {{ $u->role }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-600 font-mono text-[11px]">
                                    {{ $u->nomor_induk ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-slate-600">
                                    {{ $u->no_hp ?? '-' }}
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $u->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                        {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right whitespace-nowrap space-x-1.5">
                                    <button type="button" @click="resetTargetUser = {{ json_encode($u) }}; showResetModal = true"
                                            class="px-2 py-1 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 text-[11px] font-semibold rounded-lg transition cursor-pointer">
                                        Reset Sandi
                                    </button>
                                    <a href="{{ route('tutor.users.edit', $u) }}" class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-semibold rounded-lg transition">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">Tidak ada data pengguna sesuai kriteria pencarian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        </div>

        <!-- Modal Impor CSV (FR-08, FR-16) -->
        <div x-show="showImportModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(15, 23, 42, 0.6);">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div @click.away="showImportModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                        <h3 class="text-base font-bold text-slate-900">Impor Massal Peserta (.csv)</h3>
                        <button type="button" @click="showImportModal = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                    </div>

                    <form method="POST" action="{{ route('tutor.users.import') }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih File CSV <span class="text-rose-500">*</span></label>
                            <input type="file" name="csv_file" accept=".csv,text/csv" required
                                   class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>

                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-[11px] text-slate-600 space-y-1">
                            <span class="font-bold text-slate-800 block">Format Header CSV Wajib:</span>
                            <code>nama,username,password,nomor_induk,no_hp,kode_pelatihan</code>
                            <p class="text-slate-500 mt-1">Jika kolom password dikosongkan, sistem mengisi otomatis <code>pkbm12345</code>.</p>
                        </div>

                        <div class="flex justify-between items-center pt-2">
                            <a href="{{ route('tutor.users.template') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                                Unduh Template CSV
                            </a>
                            <div class="flex items-center gap-2">
                                <button type="button" @click="showImportModal = false" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                                    Batal
                                </button>
                                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-xs transition">
                                    Unggah & Impor
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Reset Password (FR-02) -->
        <div x-show="showResetModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(15, 23, 42, 0.6);">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div @click.away="showResetModal = false" class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl border border-slate-200">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                        <h3 class="text-base font-bold text-slate-900">Reset Kata Sandi</h3>
                        <button type="button" @click="showResetModal = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                    </div>

                    <form :action="resetTargetUser ? `/tutor/users/${resetTargetUser.id}/reset-password` : '#'" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <span class="text-xs text-slate-500 block">Pengguna:</span>
                            <span class="text-sm font-bold text-slate-900" x-text="resetTargetUser ? `${resetTargetUser.nama} (${resetTargetUser.username})` : ''"></span>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi Baru (Opsional)</label>
                            <input type="text" name="new_password" placeholder="Kosongkan untuk default: pkbm12345"
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                            <span class="text-[10px] text-slate-400 mt-1 block">Default: <code>pkbm12345</code></span>
                        </div>

                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" @click="showResetModal = false" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold rounded-xl shadow-xs transition">
                                Reset Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
