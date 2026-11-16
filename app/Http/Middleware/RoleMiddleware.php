<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->is_active) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors(['username' => 'Akun Anda dinonaktifkan. Silakan hubungi Tutor/Admin.']);
        }

        if (!in_array($user->role, $roles, true)) {
            if ($user->isTutor()) {
                return redirect()->route('tutor.dashboard')->with('error', 'Akses ditolak untuk peran Anda.');
            }
            return redirect()->route('peserta.dashboard')->with('error', 'Akses ditolak untuk peran Anda.');
        }

        return $next($request);
    }
}
