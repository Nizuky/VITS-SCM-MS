<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Auth;
use App\Models\SocialContractRecord;
use App\Models\User;
use App\Observers\SocialContractRecordObserver;
use App\Observers\UserObserver;
use App\Database\MySqlConnector;
use Illuminate\Database\Connection;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register custom MySQL connector EARLY to handle database names with special characters
        // This must happen in register() before database connections are made
        $this->app->bind('db.connector.mysql', function () {
            return new MySqlConnector;
        });
        
        Connection::resolverFor('mysql', function ($connection, $database, $prefix, $config) {
            return new \Illuminate\Database\MySqlConnection($connection, $database, $prefix, $config);
        });
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
