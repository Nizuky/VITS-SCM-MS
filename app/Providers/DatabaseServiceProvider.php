<?php

namespace App\Providers;

use App\Database\MySqlConnector;
use Illuminate\Database\Connection;
use Illuminate\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register custom MySQL connector that handles database names with special characters
        Connection::resolverFor('mysql', function ($connection, $database, $prefix, $config) {
            return new \Illuminate\Database\MySqlConnection($connection, $database, $prefix, $config);
        });
        
        $this->app->bind('db.connector.mysql', function () {
            return new MySqlConnector;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
