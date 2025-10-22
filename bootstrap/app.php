<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth;

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
        
        // Configure authentication redirect per guard (for guests trying to access protected routes)
        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            // Check which guard is being used for this request
            // This is based on the route middleware (auth:admin, auth:superadmin, auth:web)
            
            // Check request path to determine which guard should be used
            if ($request->is('admin/*') || $request->is('admin')) {
                return route('admin.login');
            }
            
            if ($request->is('super-admin/*') || $request->is('super-admin')) {
                return route('superadmin.login');
            }
            
            // Default to student login
            return route('login');
        });
        
        // Configure where authenticated users should be redirected (for RedirectIfAuthenticated middleware)
        $middleware->redirectUsersTo(function (\Illuminate\Http\Request $request) {
            // Check which guard the user is authenticated with
            if (Auth::guard('superadmin')->check()) {
                return route('superadmin.dashboard');
            }
            
            if (Auth::guard('admin')->check()) {
                return route('admin.dashboard');
            }
            
            // Default to student dashboard
            return route('dashboard');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // You can customize exception handling here if needed.
    })
    ->create();
