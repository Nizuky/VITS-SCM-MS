<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ShowDatabaseConfig extends Command
{
    protected $signature = 'env:show-db';
    protected $description = 'Show exact database configuration from environment';

    public function handle()
    {
        $this->info('Database Configuration from Environment:');
        $this->newLine();
        
        $config = [
            'DB_CONNECTION' => env('DB_CONNECTION', 'NOT SET'),
            'DB_HOST' => env('DB_HOST', 'NOT SET'),
            'DB_PORT' => env('DB_PORT', 'NOT SET'),
            'DB_DATABASE' => env('DB_DATABASE', 'NOT SET'),
            'DB_USERNAME' => env('DB_USERNAME', 'NOT SET'),
            'DB_PASSWORD' => env('DB_PASSWORD') ? str_repeat('*', min(20, strlen(env('DB_PASSWORD')))) : 'NOT SET',
            'DB_TIMEOUT' => env('DB_TIMEOUT', 'NOT SET'),
        ];
        
        foreach ($config as $key => $value) {
            $status = $value === 'NOT SET' ? '❌' : '✓';
            $this->line("  {$status} {$key}: {$value}");
        }
        
        $this->newLine();
        
        // Check what config() sees
        $this->info('Actual Configuration Being Used:');
        $dbConfig = config('database.connections.mysql');
        
        $this->line('  DB Host: ' . ($dbConfig['host'] ?? 'NOT SET'));
        $this->line('  DB Port: ' . ($dbConfig['port'] ?? 'NOT SET'));
        $this->line('  DB Database: ' . ($dbConfig['database'] ?? 'NOT SET'));
        $this->line('  DB Username: ' . ($dbConfig['username'] ?? 'NOT SET'));
        
        $this->newLine();
        
        // Critical checks
        if (env('DB_HOST') === 'NOT SET' || empty(env('DB_HOST'))) {
            $this->error('❌ CRITICAL: DB_HOST is not set in environment!');
            $this->newLine();
            $this->warn('You MUST set DB_HOST in Laravel Cloud environment to:');
            $this->line('db-a08bd7ae-1588-4461-97c3-bde906d54852.ap-southeast-1.public.db.laravel.cloud');
            return 1;
        }
        
        if (env('DB_HOST') === 'localhost' || env('DB_HOST') === '127.0.0.1') {
            $this->error('❌ CRITICAL: DB_HOST is set to localhost!');
            $this->newLine();
            $this->warn('This will not work in Laravel Cloud. Change to:');
            $this->line('db-a08bd7ae-1588-4461-97c3-bde906d54852.ap-southeast-1.public.db.laravel.cloud');
            return 1;
        }
        
        $expectedHost = 'db-a08bd7ae-1588-4461-97c3-bde906d54852.ap-southeast-1.public.db.laravel.cloud';
        if (env('DB_HOST') !== $expectedHost) {
            $this->warn('⚠️  WARNING: DB_HOST does not match expected value');
            $this->line('  Current: ' . env('DB_HOST'));
            $this->line('  Expected: ' . $expectedHost);
            $this->newLine();
            $this->line('If this is correct for your setup, ignore this warning.');
        }
        
        return 0;
    }
}
