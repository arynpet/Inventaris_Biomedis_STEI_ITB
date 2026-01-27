<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user BELUM login atau BUKAN superadmin
        if (!auth()->check() || (auth()->user()->role !== 'superadmin' && auth()->user()->role !== 'dev')) {
            abort(403, 'AKSES DITOLAK. Halaman ini khusus Super Admin.');
        }

        return $next($request);
    }
}
