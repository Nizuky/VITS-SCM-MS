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
        // Force file-based cache and cookie session in production to avoid database dependency
        // This runs before boot() and ensures config is set early
        if (env('APP_ENV') === 'production' || env('LARAVEL_CLOUD')) {
            // Force session to cookie (no database needed)
            Config::set('session.driver', 'cookie');
            
            // Force cache to file (no database needed)  
            Config::set('cache.default', 'file');
        }
    }

    /**
     * Bootstrap services.
     * 
     * Automatically switch to cookie/file drivers if database is unavailable.
     * This prevents 500 errors on Laravel Cloud when database connection fails.
     */
    public function boot(): void
    {
        // Double-check: if session driver is still 'database', verify DB is available
        if (config('session.driver') === 'database') {
            try {
                DB::connection()->getPdo();
                if (!DB::getSchemaBuilder()->hasTable('sessions')) {
                    $this->fallbackSessionToCookie('sessions table does not exist');
                }
            } catch (\Exception $e) {
                $this->fallbackSessionToCookie($e->getMessage());
            }
        }
        
        // Double-check: if cache driver is still 'database', verify DB is available
        if (config('cache.default') === 'database') {
            try {
                DB::connection()->getPdo();
                if (!DB::getSchemaBuilder()->hasTable('cache')) {
                    $this->fallbackCacheToFile('cache table does not exist');
                }
            } catch (\Exception $e) {
                $this->fallbackCacheToFile($e->getMessage());
            }
        }
    }

    /**
     * Fall back to cookie session driver
     */
    private function fallbackSessionToCookie(string $reason): void
    {
        Config::set('session.driver', 'cookie');
        
        Log::warning('Session driver automatically changed from database to cookie', [
            'reason' => $reason,
        ]);
    }
    
    /**
     * Fall back to file cache driver
     */
    private function fallbackCacheToFile(string $reason): void
    {
        Config::set('cache.default', 'file');
        
        Log::warning('Cache driver automatically changed from database to file', [
            'reason' => $reason,
        ]);
    }
}
