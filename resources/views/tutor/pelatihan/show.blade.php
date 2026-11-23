<x-app-layout title="Detail Pelatihan: {{ $pelatihan->nama }}">
    <div class="flex flex-col gap-6" x-data="{ activateModalOpen: false, activeJudul: '', activeJam: '', actionUrl: '' }">

        <!-- Header Bar -->
        <div class="bg-surface-container-lowest p-5 sm:p-6 rounded-2xl shadow-xs border border-outline-variant/30 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <a href="{{ route('tutor.pelatihan.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-primary hover:underline mb-1.5">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                    <span>Kembali ke Daftar Pelatihan</span>
                </a>
                <h1 class="text-xl sm:text-2xl font-extrabold text-on-surface tracking-tight">{{ $pelatihan->nama }}</h1>
                <div class="flex items-center gap-2 mt-1 text-xs text-on-surface-variant flex-wrap">
                    <span>Tutor: <strong class="text-on-surface">{{ $pelatihan->tutor->nama ?? 'Instruktur PKBM' }}</strong></span>
                    <span class="text-outline">•</span>
                    <span class="inline-flex items-center gap-1 font-bold text-secondary bg-secondary-container px-2 py-0.5 rounded-full text-[10px] uppercase">
                        {{ $pelatihan->status }}
                    </span>
                    <span class="text-outline">•</span>
                    <span>Peserta: <strong class="text-primary">{{ $pelatihan->pendaftarans->where('status', 'disetujui')->count() }} Warga Belajar</strong></span>
                </div>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap self-start md:self-auto">
                <a href="{{ route('tutor.pelatihan.pendaftaran', $pelatihan) }}"
                   class="px-3.5 py-2 bg-surface-container hover:bg-surface-container-high text-on-surface font-bold text-xs rounded-xl border border-outline-variant/30 transition flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px] text-primary">groups</span>
                    <span>Kelola Peserta ({{ $pelatihan->pendaftarans->count() }})</span>
                </a>
                <a href="{{ route('tutor.pelatihan.edit', $pelatihan) }}"
                   class="px-3.5 py-2 bg-surface-container hover:bg-surface-container-high text-on-surface font-bold text-xs rounded-xl border border-outline-variant/30 transition flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">edit</span>
                    <span>Edit</span>
                </a>
                <a href="{{ route('tutor.sesi.create', $pelatihan) }}"
                   class="px-4 py-2 bg-primary hover:bg-primary-container text-on-primary font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    <span>+ Tambah Sesi</span>
                </a>
            </div>
        </div>

        @if($pelatihan->deskripsi)
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 p-5 shadow-xs text-xs text-on-surface-variant">
                <span class="font-bold text-on-surface block mb-1 text-sm">Deskripsi Silabus Pembelajaran:</span>
                <p class="leading-relaxed">{{ $pelatihan->deskripsi }}</p>
            </div>
        @endif

        <!-- Sesi List Table (Stitch Style) -->
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-xs overflow-hidden">
            <div class="p-4 sm:p-5 bg-surface-container-low/70 border-b border-outline-variant/20 flex items-center justify-between">
                <div>
                    <h2 class="text-xs font-bold text-on-surface uppercase tracking-wider">Jadwal Seluruh Sesi Pembelajaran</h2>
                    <span class="text-[11px] text-on-surface-variant">Total {{ $pelatihan->sesis->count() }} pertemuan kelas terdaftar</span>
                </div>
                <a href="{{ route('tutor.sesi.create', $pelatihan) }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">add</span>
                    <span>Tambah Sesi Baru</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-on-surface">
                    <thead class="text-[11px] text-on-surface-variant uppercase bg-surface-container-low border-b border-outline-variant/20">
                        <tr>
                            <th class="py-3 px-4">Judul Sesi</th>
                            <th class="py-3 px-4">Tanggal & Jam</th>
                            <th class="py-3 px-4">Lokasi</th>
                            <th class="py-3 px-4">Toleransi</th>
                            <th class="py-3 px-4">Presensi Masuk</th>
                            <th class="py-3 px-4 text-right">Aksi QR & Sesi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/15">
                        @forelse($pelatihan->sesis as $sesi)
                            <tr class="hover:bg-surface-container-low/50 transition">
                                <td class="py-3.5 px-4 font-bold text-on-surface">
                                    {{ $sesi->judul_sesi }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="font-bold text-on-surface">{{ $sesi->tanggal->format('d/m/Y') }}</div>
                                    <div class="text-[11px] text-on-surface-variant">{{ substr((string)$sesi->jam_mulai, 0, 5) }} – {{ substr((string)$sesi->jam_selesai, 0, 5) }} WIB</div>
                                </td>
                                <td class="py-3.5 px-4 text-on-surface-variant font-medium">
                                    {{ $sesi->lokasi }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-on-surface-variant">
                                    +{{ $sesi->batas_terlambat_menit }} mnt
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="font-bold text-secondary">{{ $sesi->presensis_count }}</span> Warga Belajar
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap space-x-1.5">
                                    @if(!$sesi->isWithinAttendanceWindow())
                                        <button type="button"
                                                @click="activeJudul = '{{ addslashes($sesi->judul_sesi) }}'; activeJam = '{{ substr((string)$sesi->jam_mulai, 0, 5) }} – {{ substr((string)$sesi->jam_selesai, 0, 5) }} WIB'; actionUrl = '{{ route('tutor.sesi.activate-now', $sesi) }}'; activateModalOpen = true;"
                                                class="px-2.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-[11px] rounded-xl shadow-xs transition inline-flex items-center gap-1 cursor-pointer"
                                                title="Buka presensi sekarang">
                                            <span>⚡ Buka Sekarang</span>
                                        </button>
                                    @endif

                                    <a href="{{ route('tutor.sesi.qr', $sesi) }}"
                                       class="px-3 py-1.5 bg-primary hover:bg-primary-container text-on-primary font-bold text-[11px] rounded-xl shadow-xs transition inline-flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">qr_code_2</span>
                                        <span>Layar QR</span>
                                    </a>

                                    <a href="{{ route('tutor.sesi.live', $sesi) }}"
                                       class="px-3 py-1.5 bg-secondary hover:bg-secondary/90 text-on-secondary font-bold text-[11px] rounded-xl shadow-xs transition inline-flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">fact_check</span>
                                        <span>Live</span>
                                    </a>

                                    <a href="{{ route('tutor.sesi.edit', $sesi) }}"
                                       class="px-2.5 py-1.5 bg-surface-container hover:bg-surface-container-high text-on-surface text-[11px] font-bold rounded-xl border border-outline-variant/30 transition">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-on-surface-variant italic">
                                    Belum ada sesi yang dibuat untuk pelatihan ini. Silakan buat sesi pertama!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Pop Up Konfirmasi Buka Sesi (Tanpa Browser Alert) -->
        <div x-show="activateModalOpen" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/75 backdrop-blur-xs"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="bg-surface-container-lowest text-on-surface rounded-3xl p-6 sm:p-7 max-w-md w-full shadow-2xl border border-outline-variant/30 flex flex-col gap-4 text-left"
                 @click.outside="activateModalOpen = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[28px]">bolt</span>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-on-surface">Buka Presensi Sesi Sekarang</h3>
                        <p class="text-xs text-on-surface-variant">Aktifkan jendela presensi dan rotasi token QR</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-surface-container-low border border-outline-variant/30 text-xs text-on-surface flex flex-col gap-2">
                    <div class="font-bold text-on-surface" x-text="activeJudul"></div>
                    <div class="text-[11px] text-on-surface-variant">
                        Jadwal terdaftar: <span class="font-semibold text-on-surface" x-text="activeJam"></span>.
                    </div>
                    <div class="text-[11px] text-on-surface-variant leading-relaxed">
                        Jam sesi akan disesuaikan otomatis mulai sekarang hingga <strong>2 jam ke depan</strong>. Peserta yang sebelumnya terlewat jam presensi dapat langsung memindai QR dan mencatatkan kehadirannya.
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-1">
                    <button type="button" @click="activateModalOpen = false"
                            class="px-4 py-2.5 rounded-xl bg-surface-container hover:bg-surface-container-high text-on-surface text-xs font-bold transition cursor-pointer">
                        Batal
                    </button>
                    <form :action="actionUrl" method="POST">
                        @csrf
                        <button type="submit"
                                class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-extrabold text-xs shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                            <span class="material-symbols-outlined text-[18px]">bolt</span>
                            <span>Ya, Buka Sesi Sekarang</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
