<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // <--- TAMBAHAN PENTING
use Illuminate\Support\Facades\URL; // <--- INI BARIS YANG TADI KURANG!

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    // Pastikan baris ini ada di paling atas file

    public function boot(): void
    {
        // 1. Ambil URL asli dari .env (https://aorukudomain.my.id)
        $appUrl = config('app.url');

        // 2. Jika URL mengandung HTTPS, paksa skema aman
        if (str_contains($appUrl, 'https')) {
            URL::forceScheme('https');
        }

        // 3. Paksa Root URL untuk semua link (Redirect, Asset, Route)
        URL::forceRootUrl($appUrl);

        // 4. [FIX PAGINATION] Paksa Paginator menggunakan domain publik
        // Ini memperbaiki tombol "Page 2" yang nyasar ke biomed.local
        Paginator::currentPathResolver(function () use ($appUrl) {
            return $appUrl . '/' . request()->path();
        });
    }
}
