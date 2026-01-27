<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDevMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        // Cek role 'dev'
        if (auth()->user()->role !== 'dev') {
            // Bisa redirect ke 403 atau dashboard
            if (auth()->user()->role === 'superadmin' || auth()->user()->is_dev_mode) {
                // Allow superadmin or checks existing is_dev_mode column as fallback so we don't lock out existing superusers
            } else {
                abort(403, 'Akses Ditolak. Halaman ini hanya untuk Developer.');
            }
        }

        return $next($request);
    }
}
