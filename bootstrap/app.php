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
            __DIR__.'/../routes/test-db.php',
            __DIR__.'/../routes/emergency.php',
        ],
        api: null,
        commands: null,
        channels: null,
        pages: null,
        health: '/up',
    )
    ->withProviders([
        App\Providers\AppServiceProvider::class,
        App\Providers\SessionDriverProvider::class, // Auto-fallback to cookie if DB unavailable
        App\Providers\DatabaseMigrationProvider::class, // Auto-add missing columns on Laravel Cloud
        Livewire\LivewireServiceProvider::class,
        Livewire\Volt\VoltServiceProvider::class,
        App\Providers\FortifyViewServiceProvider::class,
        App\Providers\VoltServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware) {
        // Exclude specific routes from CSRF verification
        // All dashboard API routes are excluded since they use session-based auth
        // NOTE: Livewire routes (/livewire/update, /livewire/upload) should NOT be excluded
        // They need CSRF tokens which are sent via headers
        $middleware->validateCsrfTokens(except: [
            // Student dashboard routes - all CRUD operations
            '/api/social-contract/records',
            '/api/social-contract/records/*',
            '/api/support-tickets',
            '/api/support-tickets/*',
            '/api/notifications/*',
            '/api/profile/*',
            
            // Admin dashboard routes - all operations
            '/admin/api/*',
            '/admin/data-management/*',
            
            // Super Admin dashboard routes - all operations
            '/super-admin/api/*',
        ]);
        
        // NOTE: ForcePendingLogout middleware removed - it caused session errors on Laravel Cloud
        // with ephemeral filesystems. The client-side logout handling is sufficient.
        
        // NOTE: RefreshSessionActivity middleware removed - it caused issues with ephemeral filesystems
        // on Laravel Cloud when SESSION_DRIVER=file. Laravel's built-in session handling is sufficient.
        
        // CRITICAL: Isolate web guard session during registration to prevent
        // Fortify's auto-login from affecting admin/superadmin sessions
        $middleware->appendToGroup('web', App\Http\Middleware\IsolateWebGuardSession::class);
        
        // Keep session alive - updates last activity timestamp on every request
        $middleware->appendToGroup('web', App\Http\Middleware\KeepSessionAlive::class);
        
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
