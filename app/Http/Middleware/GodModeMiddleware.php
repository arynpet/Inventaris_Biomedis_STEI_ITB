<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GodModeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if session has the god mode unlock key
        if (!session()->has('god_mode_unlocked') || session('god_mode_unlocked') !== true) {
            return redirect()->route('dev.tools.login');
        }

        return $next($request);
    }
}
