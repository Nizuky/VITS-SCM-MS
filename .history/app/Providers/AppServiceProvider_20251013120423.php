<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Auth;

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
    public function boot(): void
    {
        // Redirect authenticated users to their guard-specific dashboards
        RedirectIfAuthenticated::redirectUsing(function ($request) {
            // Superadmin guard
            if (Auth::guard('superadmin')->check() && 
                \Illuminate\Support\Facades\Route::has('superadmin.dashboard')) {
                return route('superadmin.dashboard');
            }

            // Admin guard
            if (Auth::guard('admin')->check() && 
                \Illuminate\Support\Facades\Route::has('admin.dashboard')) {
                return route('admin.dashboard');
            }

            // Default to the main dashboard or home
            if (\Illuminate\Support\Facades\Route::has('dashboard')) {
                return route('dashboard');
            }

            return '/';
        });
    }
}
