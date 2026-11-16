<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->isTutor()
                ? redirect()->route('tutor.dashboard')
                : redirect()->route('peserta.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $throttleKey = 'login:' . $request->ip();

        // FR-10 / PRD Sec 10: Rate limiting pada login (maksimal 5 percobaan per menit)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'username' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
            ]);
        }

        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                throw ValidationException::withMessages([
                    'username' => 'Akun Anda berstatus nonaktif. Silakan hubungi Tutor.',
                ]);
            }

            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            return $user->isTutor()
                ? redirect()->intended(route('tutor.dashboard'))
                : redirect()->intended(route('peserta.dashboard'));
        }

        RateLimiter::hit($throttleKey, 60);

        throw ValidationException::withMessages([
            'username' => 'Username atau password yang Anda masukkan salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }

    public function showProfile()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'nama' => ['required', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:20'],
        ];

        if ($request->filled('password_baru')) {
            $rules['password_lama'] = ['required', 'current_password'];
            $rules['password_baru'] = ['required', 'string', 'min:6', 'confirmed'];
        }

        $validated = $request->validate($rules);

        $user->nama = $validated['nama'];
        $user->no_hp = $validated['no_hp'] ?? null;

        if ($request->filled('password_baru')) {
            $user->password = Hash::make($validated['password_baru']);
        }

        $user->save();

        return back()->with('success', 'Profil dan kata sandi berhasil diperbarui.');
    }
}
