<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->web(append: [
            // \App\Http\Middleware\EnableDebugbar::class, // REMOVED
        ]);
        $middleware->alias([
            'superadmin' => \App\Http\Middleware\IsSuperAdmin::class,
            'dev.mode' => \App\Http\Middleware\CheckDevMode::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            '/user/heartbeat',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
