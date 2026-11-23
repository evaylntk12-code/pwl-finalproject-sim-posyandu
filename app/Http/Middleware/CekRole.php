<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware authorization berbasis role (RBAC).
 *
 * Pemakaian di route:
 *   ->middleware('role:bidan')          satu role
 *   ->middleware('role:kader, bidan')    salah satu dari beberapa role
 *
 * Perbedaan dengan middleware 'auth':
 *   auth = authentication (sudah login atau belum -> kalau belum, redirect ke login / 401)
 *   role = authorization  (sudah login, tetapi bolehkah role ini? -> kalau tidak, 403)
 */
class CekRole
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // Belum login: identitas tidak diketahui (401 Unauthorized).
        // Biasanya sudah dicegat middleware 'auth' lebih dulu; ini pengaman tambahan.
        abort_if(is_null($user), 401, 'Silakan login terlebih dahulu.');

        // Sudah login tetapi role tidak diizinkan: terlarang (403 Forbidden)
        abort_unless($user->hasRole(...$roles), 403, 'Anda tidak berhak membuka halaman ini.');

        return $next($request);
    }
}