<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Pelatihan;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->query('role');
        $search = $request->query('q');

        $users = User::when($role, fn ($q) => $q->where('role', $role))
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%")
                      ->orWhere('nomor_induk', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15);

        return view('tutor.users.index', compact('users', 'role', 'search'));
    }

    public function create()
    {
        $pelatihans = Pelatihan::where('status', 'aktif')->orderBy('nama')->get();
        return view('tutor.users.create', compact('pelatihans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'in:peserta,tutor'],
            'nomor_induk' => ['nullable', 'string', 'max:50'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'is_active' => ['nullable', 'boolean'],
            'pelatihan_id' => ['nullable', 'exists:pelatihans,id'],
        ]);

        $password = !empty($validated['password']) ? $validated['password'] : 'pkbm12345';

        $user = User::create([
            'nama' => $validated['nama'],
            'username' => $validated['username'],
            'password' => Hash::make($password),
            'role' => $validated['role'],
            'nomor_induk' => $validated['nomor_induk'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Jika dipilih pelatihan untuk peserta, daftarkan otomatis
        if ($user->isPeserta() && !empty($validated['pelatihan_id'])) {
            Pendaftaran::create([
                'user_id' => $user->id,
                'pelatihan_id' => $validated['pelatihan_id'],
                'status' => 'disetujui',
                'tanggal_daftar' => now(),
            ]);
        }

        return redirect()->route('tutor.users.index')
            ->with('success', "Pengguna {$user->nama} ({$user->username}) berhasil dibuat dengan password awal: {$password}");
    }

    public function edit(User $user)
    {
        return view('tutor.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
            'role' => ['required', 'in:peserta,tutor'],
            'nomor_induk' => ['nullable', 'string', 'max:50'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $user->update([
            'nama' => $validated['nama'],
            'username' => $validated['username'],
            'role' => $validated['role'],
            'nomor_induk' => $validated['nomor_induk'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('tutor.users.index')
            ->with('success', "Data pengguna {$user->nama} berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $nama = $user->nama;
        $user->delete();

        return redirect()->route('tutor.users.index')
            ->with('success', "Pengguna {$nama} berhasil dihapus.");
    }

    public function resetPassword(Request $request, User $user)
    {
        $password = $request->filled('new_password') ? $request->input('new_password') : 'pkbm12345';
        $user->password = Hash::make($password);
        $user->save();

        return back()->with('success', "Kata sandi untuk {$user->nama} ({$user->username}) berhasil direset menjadi: {$password}");
    }

    /**
     * Download format template CSV untuk impor peserta
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_impor_peserta_pkbm.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM agar rapi di Microsoft Excel
            fputs($handle, "\xEF\xBB\xBF");

            // Header kolom sesuai PRD Section 9.1
            fputcsv($handle, ['nama', 'username', 'password', 'nomor_induk', 'no_hp', 'kode_pelatihan']);

            // Baris contoh data
            fputcsv($handle, ['Budi Santoso', 'budi_santoso', 'pkbm12345', 'WB-2026-010', '081234567890', '1']);
            fputcsv($handle, ['Siti Aminah', 'siti_aminah', '', 'WB-2026-011', '081234567891', '']);

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Impor Peserta secara massal dari file CSV
     */
    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);

        $file = $request->file('csv_file');
        $filePath = $file->getRealPath();

        $rows = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            // Deteksi UTF-8 BOM
            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") {
                rewind($handle);
            }

            $headers = null;
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                // Bersihkan karakter kosong
                $data = array_map('trim', $data);

                if (!$headers) {
                    $headers = array_map('strtolower', $data);
                    continue;
                }

                if (count($data) >= 2 && !empty($data[0])) {
                    $row = [];
                    foreach ($headers as $index => $header) {
                        $row[$header] = $data[$index] ?? '';
                    }
                    $rows[] = $row;
                }
            }
            fclose($handle);
        }

        $importedCount = 0;
        $skippedCount = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $nama = $row['nama'] ?? '';
            $username = $row['username'] ?? '';
            $password = !empty($row['password']) ? $row['password'] : 'pkbm12345';
            $nomorInduk = $row['nomor_induk'] ?? null;
            $noHp = $row['no_hp'] ?? null;
            $kodePelatihan = $row['kode_pelatihan'] ?? null;

            if (empty($nama) || empty($username)) {
                $skippedCount++;
                $errors[] = "Baris " . ($index + 2) . ": Nama atau username kosong.";
                continue;
            }

            if (User::where('username', $username)->exists()) {
                $skippedCount++;
                $errors[] = "Baris " . ($index + 2) . ": Username '{$username}' sudah terdaftar.";
                continue;
            }

            $user = User::create([
                'nama' => $nama,
                'username' => $username,
                'password' => Hash::make($password),
                'role' => 'peserta',
                'nomor_induk' => $nomorInduk,
                'no_hp' => $noHp,
                'is_active' => true,
            ]);

            // Jika ada kode_pelatihan
            if (!empty($kodePelatihan)) {
                $pelatihan = Pelatihan::where('id', $kodePelatihan)
                    ->orWhere('nama', $kodePelatihan)
                    ->first();

                if ($pelatihan) {
                    Pendaftaran::firstOrCreate([
                        'user_id' => $user->id,
                        'pelatihan_id' => $pelatihan->id,
                    ], [
                        'status' => 'disetujui',
                        'tanggal_daftar' => now(),
                    ]);
                }
            }

            $importedCount++;
        }

        $message = "Berhasil mengimpor {$importedCount} akun warga belajar.";
        if ($skippedCount > 0) {
            $message .= " ({$skippedCount} baris dilewati karena duplikasi/tidak valid).";
        }

        return redirect()->route('tutor.users.index')
            ->with('success', $message)
            ->with('import_errors', $errors);
    }
}
