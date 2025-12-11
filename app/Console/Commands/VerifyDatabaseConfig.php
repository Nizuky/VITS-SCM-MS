<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class VerifyDatabaseConfig extends Command
{
    protected $signature = 'db:verify-config';
    protected $description = 'Verify database configuration and environment variables';

    public function handle()
    {
        $this->info('Database Configuration Verification');
        $this->newLine();

        // Display environment
        $this->info('Environment: ' . app()->environment());
        $this->newLine();

        // Check database configuration
        $this->info('Database Configuration:');
        $config = Config::get('database.connections.mysql');
        
        $this->table(
            ['Setting', 'Value', 'Source'],
            [
                ['Connection', Config::get('database.default'), 'database.default'],
                ['Host', $config['host'] ?? 'NOT SET', 'DB_HOST'],
                ['Port', $config['port'] ?? 'NOT SET', 'DB_PORT'],
                ['Database', $config['database'] ?? 'NOT SET', 'DB_DATABASE'],
                ['Username', $config['username'] ?? 'NOT SET', 'DB_USERNAME'],
                ['Password', $config['password'] ? str_repeat('*', min(strlen($config['password']), 20)) : 'NOT SET', 'DB_PASSWORD'],
                ['Unix Socket', $config['unix_socket'] ?? 'not used', 'DB_SOCKET'],
                ['Charset', $config['charset'] ?? 'NOT SET', 'fixed'],
                ['Collation', $config['collation'] ?? 'NOT SET', 'fixed'],
            ]
        );
        $this->newLine();

        // Check timeout settings
        $this->info('Timeout Configuration:');
        $options = $config['options'] ?? [];
        $this->table(
            ['Setting', 'Value'],
            [
                ['PDO::ATTR_TIMEOUT', $options[\PDO::ATTR_TIMEOUT] ?? 'not set'],
                ['PDO::ATTR_PERSISTENT', isset($options[\PDO::ATTR_PERSISTENT]) ? ($options[\PDO::ATTR_PERSISTENT] ? 'true' : 'false') : 'not set'],
                ['PDO::ATTR_ERRMODE', isset($options[\PDO::ATTR_ERRMODE]) ? 'ERRMODE_EXCEPTION' : 'not set'],
            ]
        );
        $this->newLine();

        // Check if we can resolve the host
        $this->info('Network Diagnostics:');
        $host = $config['host'] ?? null;
        
        if ($host && $host !== '127.0.0.1' && $host !== 'localhost') {
            $this->line("Attempting DNS resolution for: {$host}");
            
            $dnsResult = gethostbyname($host);
            if ($dnsResult === $host) {
                $this->error("  ✗ DNS resolution failed - host not found");
            } else {
                $this->line("  ✓ DNS resolved to: {$dnsResult}");
            }
            $this->newLine();
        }

        // Test actual connection
        $this->info('Connection Test:');
        try {
            $start = microtime(true);
            $pdo = DB::connection()->getPdo();
            $time = round((microtime(true) - $start) * 1000, 2);
            
            $this->line("  ✓ Successfully connected to database in {$time}ms");
            
            // Get server version
            $version = DB::select('SELECT VERSION() as version')[0]->version ?? 'unknown';
            $this->line("  ✓ MySQL version: {$version}");
            
            // Get current database
            $currentDb = DB::select('SELECT DATABASE() as db')[0]->db ?? 'unknown';
            $this->line("  ✓ Current database: {$currentDb}");
            
            $this->newLine();
            $this->info('✓ Database configuration is correct and connection is working!');
            
            return 0;
        } catch (\PDOException $e) {
            $this->newLine();
            $this->error('✗ Database connection failed!');
            $this->newLine();
            $this->error('Error Code: ' . $e->getCode());
            $this->error('Error Message: ' . $e->getMessage());
            $this->newLine();
            
            // Provide specific troubleshooting advice based on error
            if (str_contains($e->getMessage(), 'timed out') || str_contains($e->getMessage(), 'Connection refused')) {
                $this->warn('Troubleshooting Steps:');
                $this->line('1. Verify DB_HOST is correct in your environment');
                $this->line('2. Check if database server is running');
                $this->line('3. Verify firewall allows connections from this server');
                $this->line('4. Check if database is on correct port (default: 3306)');
                $this->line('5. Ensure network route exists between app and database');
            } elseif (str_contains($e->getMessage(), 'Access denied')) {
                $this->warn('Troubleshooting Steps:');
                $this->line('1. Verify DB_USERNAME is correct');
                $this->line('2. Verify DB_PASSWORD is correct');
                $this->line('3. Check database user has permissions for this database');
                $this->line('4. Verify user is allowed to connect from this host');
            } elseif (str_contains($e->getMessage(), 'Unknown database')) {
                $this->warn('Troubleshooting Steps:');
                $this->line('1. Verify DB_DATABASE name is correct');
                $this->line('2. Create the database if it doesn\'t exist');
                $this->line('3. Check database user has access to this database');
            }
            
            return 1;
        } catch (\Exception $e) {
            $this->newLine();
            $this->error('✗ Unexpected error occurred!');
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
}
