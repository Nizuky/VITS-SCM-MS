<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class DatabaseMigrationProvider extends ServiceProvider
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
     * Auto-run missing migrations that can't be run via artisan on Laravel Cloud.
     */
    public function boot(): void
    {
        // Only run in production and after the first few requests to avoid slowing down every request
        if (app()->runningInConsole()) {
            return;
        }

        try {
            // Check if database is accessible
            DB::connection()->getPdo();
            
            // Check if users table exists and add missing columns
            if (Schema::hasTable('users')) {
                $this->ensureWarningColumnsExist();
            }
        } catch (\Exception $e) {
            // Database not accessible, skip
            Log::debug('DatabaseMigrationProvider: Could not check migrations', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Ensure warning_level and flagged_for_deletion columns exist on users table
     */
    private function ensureWarningColumnsExist(): void
    {
        try {
            // Use a cache flag to avoid checking on every request
            $cacheKey = 'db_migration_warning_columns_checked';
            
            if (cache()->has($cacheKey)) {
                return;
            }

            $hasWarningLevel = Schema::hasColumn('users', 'warning_level');
            $hasFlaggedForDeletion = Schema::hasColumn('users', 'flagged_for_deletion');

            if (!$hasWarningLevel || !$hasFlaggedForDeletion) {
                Log::info('DatabaseMigrationProvider: Adding missing warning columns to users table');

                Schema::table('users', function ($table) use ($hasWarningLevel, $hasFlaggedForDeletion) {
                    if (!$hasWarningLevel) {
                        $table->unsignedTinyInteger('warning_level')->default(0)->after('status');
                    }
                    if (!$hasFlaggedForDeletion) {
                        $table->boolean('flagged_for_deletion')->default(false)->after('warning_level');
                    }
                });

                Log::info('DatabaseMigrationProvider: Successfully added warning columns');
            }

            // Cache for 24 hours so we don't check on every request
            cache()->put($cacheKey, true, now()->addHours(24));

        } catch (\Exception $e) {
            Log::error('DatabaseMigrationProvider: Failed to add warning columns', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
