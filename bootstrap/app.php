<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__.'/../routes/web.php',
            __DIR__.'/../routes/auth.php',
        ],
        api: null,
        commands: null,
        channels: null,
        pages: null,
        health: '/up',
    )
    ->withProviders([
        App\Providers\AppServiceProvider::class,
        Livewire\LivewireServiceProvider::class,
        Livewire\Volt\VoltServiceProvider::class,
        App\Providers\FortifyViewServiceProvider::class,
        App\Providers\VoltServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware to enforce logout if the client set a pending flag (fallback when beacon is dropped)
        $middleware->append(App\Http\Middleware\ForcePendingLogout::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // You can customize exception handling here if needed.
    })
    ->create();
