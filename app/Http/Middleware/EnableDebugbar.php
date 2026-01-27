<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Barryvdh\Debugbar\Facades\Debugbar;

class EnableDebugbar
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Default: Disable Debugbar for everyone
        if (class_exists(Debugbar::class)) {
            Debugbar::disable();

            // Enable ONLY if user is logged in AND has Dev Mode active
            if (auth()->check()) {
                $user = auth()->user();
                if ($user->isSuperAdmin() || $user->is_dev_mode) {
                    Debugbar::enable();
                }
            }
        }

        return $next($request);
    }
}
