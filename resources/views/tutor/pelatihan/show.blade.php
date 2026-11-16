<x-app-layout title="Detail Pelatihan: {{ $pelatihan->nama }}">
    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-200">
            <div>
                <a href="{{ route('tutor.pelatihan.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition mb-1">
                    &larr; Kembali ke Daftar Pelatihan
                </a>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">{{ $pelatihan->nama }}</h1>
                <p class="text-xs text-slate-500">Tutor: <strong>{{ $pelatihan->tutor->nama ?? '-' }}</strong> &bull; Status: <span class="uppercase font-bold text-emerald-700">{{ $pelatihan->status }}</span></p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('tutor.pelatihan.pendaftaran', $pelatihan) }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-xs rounded-xl transition">
                    Kelola Peserta ({{ $pelatihan->pendaftarans->count() }})
                </a>
                <a href="{{ route('tutor.pelatihan.edit', $pelatihan) }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-xs rounded-xl transition">
                    Edit Pelatihan
                </a>
                <a href="{{ route('tutor.sesi.create', $pelatihan) }}" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition">
                    + Tambah Sesi
                </a>
            </div>
        </div>

        @if($pelatihan->deskripsi)
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs text-xs text-slate-600">
                <span class="font-bold text-slate-800 block mb-1">Deskripsi Silabus & Tujuan:</span>
                <p class="leading-relaxed">{{ $pelatihan->deskripsi }}</p>
            </div>
        @endif

        <!-- Sesi List -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Jadwal Seluruh Sesi Pembelajaran</h2>
                    <span class="text-[11px] text-slate-500">Total {{ $pelatihan->sesis->count() }} pertemuan terdaftar</span>
                </div>
                <a href="{{ route('tutor.sesi.create', $pelatihan) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                    + Sesi Baru
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="text-[11px] text-slate-400 uppercase bg-slate-50/50 border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4">Judul Sesi</th>
                            <th class="py-3 px-4">Tanggal & Jam</th>
                            <th class="py-3 px-4">Lokasi</th>
                            <th class="py-3 px-4">Toleransi Terlambat</th>
                            <th class="py-3 px-4">Presensi Masuk</th>
                            <th class="py-3 px-4 text-right">Aksi QR & Sesi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pelatihan->sesis as $sesi)
                            <tr class="hover:bg-slate-50/60">
                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    {{ $sesi->judul_sesi }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="font-medium text-slate-800">{{ $sesi->tanggal->format('d/m/Y') }}</div>
                                    <div class="text-[11px] text-slate-500">{{ substr((string)$sesi->jam_mulai, 0, 5) }} – {{ substr((string)$sesi->jam_selesai, 0, 5) }} WIB</div>
                                </td>
                                <td class="py-3.5 px-4 text-slate-700">
                                    {{ $sesi->lokasi }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    {{ $sesi->batas_terlambat_menit }} menit
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="font-bold text-emerald-700">{{ $sesi->presensis_count }}</span> Peserta
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap space-x-1.5">
                                    <a href="{{ route('tutor.sesi.qr', $sesi) }}" target="_blank"
                                       class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-[11px] rounded-lg transition inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                                        QR Layar Penuh
                                    </a>
                                    <a href="{{ route('tutor.sesi.live', $sesi) }}"
                                       class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-[11px] rounded-lg transition inline-flex items-center gap-1">
                                        Live
                                    </a>
                                    <a href="{{ route('tutor.sesi.edit', $sesi) }}" class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-semibold rounded-lg transition">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">Belum ada sesi pada pelatihan ini. Klik "+ Tambah Sesi" di atas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
