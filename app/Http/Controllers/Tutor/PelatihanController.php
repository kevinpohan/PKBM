<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Pelatihan;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelatihanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('q');

        $pelatihans = Pelatihan::with('tutor')
            ->withCount([
                'sesis',
                'pesertas as peserta_disetujui_count' => function ($q) {
                    $q->where('pendaftarans.status', 'disetujui');
                },
                'pendaftarans as pendaftaran_menunggu_count' => function ($q) {
                    $q->where('status', 'menunggu');
                },
            ])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10);

        return view('tutor.pelatihan.index', compact('pelatihans', 'status', 'search'));
    }

    public function create()
    {
        $tutors = User::where('role', 'tutor')->where('is_active', true)->orderBy('nama')->get();
        return view('tutor.pelatihan.create', compact('tutors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'tutor_id' => ['required', 'exists:users,id'],
            'deskripsi' => ['nullable', 'string'],
            'kuota' => ['nullable', 'integer', 'min:1'],
            'butuh_persetujuan' => ['nullable', 'boolean'],
            'status' => ['required', 'in:aktif,arsip'],
        ]);

        $validated['butuh_persetujuan'] = $request->boolean('butuh_persetujuan');

        $pelatihan = Pelatihan::create($validated);

        return redirect()->route('tutor.pelatihan.index')
            ->with('success', "Pelatihan '{$pelatihan->nama}' berhasil dibuat.");
    }

    public function show(Pelatihan $pelatihan)
    {
        $pelatihan->load(['tutor', 'sesis' => function ($q) {
            $q->withCount('presensis')->orderBy('tanggal', 'asc')->orderBy('jam_mulai', 'asc');
        }, 'pendaftarans.user']);

        return view('tutor.pelatihan.show', compact('pelatihan'));
    }

    public function edit(Pelatihan $pelatihan)
    {
        $tutors = User::where('role', 'tutor')->where('is_active', true)->orderBy('nama')->get();
        return view('tutor.pelatihan.edit', compact('pelatihan', 'tutors'));
    }

    public function update(Request $request, Pelatihan $pelatihan)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'tutor_id' => ['required', 'exists:users,id'],
            'deskripsi' => ['nullable', 'string'],
            'kuota' => ['nullable', 'integer', 'min:1'],
            'butuh_persetujuan' => ['nullable', 'boolean'],
            'status' => ['required', 'in:aktif,arsip'],
        ]);

        $validated['butuh_persetujuan'] = $request->boolean('butuh_persetujuan');

        $pelatihan->update($validated);

        return redirect()->route('tutor.pelatihan.show', $pelatihan)
            ->with('success', "Pelatihan '{$pelatihan->nama}' berhasil diperbarui.");
    }

    public function destroy(Pelatihan $pelatihan)
    {
        $nama = $pelatihan->nama;
        $pelatihan->delete();

        return redirect()->route('tutor.pelatihan.index')
            ->with('success', "Pelatihan '{$nama}' berhasil dihapus.");
    }

    public function pendaftarans(Pelatihan $pelatihan)
    {
        $pendaftarans = Pendaftaran::with('user')
            ->where('pelatihan_id', $pelatihan->id)
            ->latest('tanggal_daftar')
            ->get();

        return view('tutor.pelatihan.pendaftaran', compact('pelatihan', 'pendaftarans'));
    }

    public function approvePendaftaran(Pendaftaran $pendaftaran)
    {
        // Cek kuota pelatihan
        $pelatihan = $pendaftaran->pelatihan;
        if ($pendaftaran->status !== 'disetujui' && $pelatihan->isPenuh()) {
            return back()->with('error', 'Gagal menyetujui: kuota pelatihan sudah penuh.');
        }

        $pendaftaran->update(['status' => 'disetujui']);

        return back()->with('success', "Pendaftaran {$pendaftaran->user->nama} berhasil disetujui.");
    }

    public function rejectPendaftaran(Pendaftaran $pendaftaran)
    {
        $pendaftaran->update(['status' => 'ditolak']);

        return back()->with('success', "Pendaftaran {$pendaftaran->user->nama} telah ditolak.");
    }
}
