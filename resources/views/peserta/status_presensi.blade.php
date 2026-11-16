<x-app-layout title="Konfirmasi Presensi">
    <div class="max-w-md mx-auto py-4">

        @if($result['success'] ?? false)
            <!-- Sukses: Layar Konfirmasi Hijau (FR-09) -->
            <div class="bg-white rounded-3xl border-2 border-emerald-500 shadow-xl shadow-emerald-500/10 overflow-hidden text-center">
                
                <div class="bg-gradient-to-b from-emerald-500 to-emerald-600 p-8 text-white">
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 border border-white/40 shadow-inner">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/20 border border-white/30 mb-2">
                        Presensi Berhasil Dicatat
                    </span>
                    <h1 class="text-2xl font-black tracking-tight">Status: {{ strtoupper($result['status']) }}</h1>
                    @if($result['status'] === 'terlambat')
                        <p class="text-emerald-100 text-xs mt-1">(Tercatat melewati batas toleransi keterlambatan)</p>
                    @endif
                </div>

                <div class="p-6 space-y-4 text-left">
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200/80 space-y-2.5 text-xs">
                        <div class="flex justify-between items-center py-1 border-b border-slate-200/60">
                            <span class="text-slate-500">Nama Peserta</span>
                            <span class="font-bold text-slate-900">{{ $result['nama_peserta'] }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1 border-b border-slate-200/60">
                            <span class="text-slate-500">Sesi Pembelajaran</span>
                            <span class="font-semibold text-slate-800 text-right max-w-[200px] truncate">{{ $result['nama_sesi'] }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1 border-b border-slate-200/60">
                            <span class="text-slate-500">Pelatihan</span>
                            <span class="font-medium text-slate-700 text-right max-w-[200px] truncate">{{ $result['nama_pelatihan'] }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1 border-b border-slate-200/60">
                            <span class="text-slate-500">Waktu Server</span>
                            <span class="font-mono font-bold text-emerald-700">{{ $result['jam_server'] }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1 border-b border-slate-200/60">
                            <span class="text-slate-500">Tanggal</span>
                            <span class="font-medium text-slate-700">{{ $result['tanggal'] }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="text-slate-500">Metode</span>
                            <span class="font-medium text-slate-700">{{ $result['metode'] }}</span>
                        </div>
                    </div>

                    <div class="space-y-2 pt-2">
                        <a href="{{ route('peserta.dashboard') }}" class="block w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl text-center shadow-md shadow-emerald-600/20 transition">
                            Kembali ke Dashboard
                        </a>
                        <a href="{{ route('peserta.riwayat') }}" class="block w-full py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl text-center transition">
                            Lihat Riwayat Kehadiran
                        </a>
                    </div>
                </div>

            </div>
        @else
            <!-- Gagal: Layar Penolakan Merah (FR-09) -->
            <div class="bg-white rounded-3xl border-2 border-rose-500 shadow-xl shadow-rose-500/10 overflow-hidden text-center">
                
                <div class="bg-gradient-to-b from-rose-500 to-rose-600 p-8 text-white">
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 border border-white/40 shadow-inner">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                    </div>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/20 border border-white/30 mb-2">
                        Presensi Gagal / Ditolak
                    </span>
                    <h1 class="text-xl font-black tracking-tight">Presensi Tidak Berhasil</h1>
                </div>

                <div class="p-6 space-y-5 text-left">
                    <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4">
                        <span class="text-[11px] font-bold text-rose-700 uppercase tracking-wider block mb-1">Penyebab Kegagalan:</span>
                        <p class="text-xs font-semibold text-rose-900 leading-relaxed">
                            {{ $result['alasan'] ?? 'Terjadi kesalahan validasi presensi.' }}
                        </p>
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200/80 space-y-2 text-xs">
                        <div class="flex justify-between items-center py-1 border-b border-slate-200/60">
                            <span class="text-slate-500">Nama Peserta</span>
                            <span class="font-bold text-slate-800">{{ $result['nama_peserta'] ?? auth()->user()->nama }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="text-slate-500">Sesi</span>
                            <span class="font-semibold text-slate-800">{{ $result['nama_sesi'] ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <a href="{{ route('peserta.dashboard') }}" class="block w-full py-3 px-4 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-xl text-center shadow-xs transition">
                            Kembali ke Dashboard
                        </a>
                        <a href="{{ url()->previous() }}" class="block w-full py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl text-center transition">
                            Coba Pindai Ulang
                        </a>
                    </div>
                </div>

            </div>
        @endif

    </div>
</x-app-layout>
