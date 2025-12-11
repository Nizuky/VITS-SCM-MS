<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Auth;
use App\Models\SocialContractRecord;
use App\Models\User;
use App\Observers\SocialContractRecordObserver;
use App\Observers\UserObserver;

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
        // Do NOT customize RedirectIfAuthenticated globally
        // Each guard will handle its own redirects via guard-specific middleware
        
        // Register observers
        SocialContractRecord::observe(SocialContractRecordObserver::class);
        User::observe(UserObserver::class);
    }
}
