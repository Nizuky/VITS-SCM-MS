<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class SessionDriverProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     * 
     * Automatically switch to cookie driver if database is unavailable.
     * This prevents 500 errors on Laravel Cloud when database connection fails.
     */
    public function boot(): void
    {
        // Only check if session driver is set to 'database'
        if (config('session.driver') === 'database') {
            try {
                // Quick check if database is available (with 1 second timeout)
                DB::connection()->getPdo();
                
                // Additionally check if sessions table exists
                if (!DB::getSchemaBuilder()->hasTable('sessions')) {
                    // Table doesn't exist, fall back to cookie
                    $this->fallbackToCookieDriver('sessions table does not exist');
                }
            } catch (\Exception $e) {
                // Database not available, fall back to cookie driver
                $this->fallbackToCookieDriver($e->getMessage());
            }
        }
    }

    /**
     * Fall back to cookie session driver
     */
    private function fallbackToCookieDriver(string $reason): void
    {
        Config::set('session.driver', 'cookie');
        
        Log::warning('Session driver automatically changed from database to cookie', [
            'reason' => $reason,
            'original_driver' => 'database',
            'new_driver' => 'cookie'
        ]);
    }
}
