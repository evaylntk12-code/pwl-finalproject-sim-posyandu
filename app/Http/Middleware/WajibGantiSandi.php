<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WajibGantiSandi
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Akun baru atau yang baru direset wajib mengganti kata sandi dulu.
        // Halaman ganti sandi itu sendiri tetap boleh dibuka.
        if ($user && $user->must_change_password && ! $request->routeIs('*.sandi', '*.sandi.perbarui')) {
            return redirect()->route($user->role === 'kader' ? 'kader.sandi' : 'ortu.sandi');
        }

        return $next($request);
    }
}
