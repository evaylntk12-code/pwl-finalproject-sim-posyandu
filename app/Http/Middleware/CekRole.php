<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Kader tidak boleh membuka halaman orang tua, dan sebaliknya
        abort_if($request->user()->role !== $role, 403, 'Anda tidak berhak membuka halaman ini.');

        return $next($request);
    }
}
