<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestDatabaseConnection extends Command
{
    protected $signature = 'db:test-connection';
    protected $description = 'Test database connection and query performance';

    public function handle()
    {
        $this->info('Testing database connection...');
        $this->newLine();

        // Display configuration
        $this->info('Database Configuration:');
        $this->line('  Host: ' . config('database.connections.mysql.host'));
        $this->line('  Port: ' . config('database.connections.mysql.port'));
        $this->line('  Database: ' . config('database.connections.mysql.database'));
        $this->line('  Username: ' . config('database.connections.mysql.username'));
        $this->line('  Timeout: ' . (config('database.connections.mysql.options')[PDO::ATTR_TIMEOUT] ?? 'not set'));
        $this->newLine();

        // Test 1: Basic PDO connection
        $this->info('Test 1: Basic PDO Connection');
        try {
            $start = microtime(true);
            $pdo = DB::connection()->getPdo();
            $time = round((microtime(true) - $start) * 1000, 2);
            $this->line("  ✓ Connected successfully in {$time}ms");
        } catch (\Exception $e) {
            $this->error("  ✗ Connection failed: " . $e->getMessage());
            return 1;
        }
        $this->newLine();

        // Test 2: Simple query
        $this->info('Test 2: Simple SELECT Query');
        try {
            $start = microtime(true);
            $result = DB::select('SELECT 1 as test');
            $time = round((microtime(true) - $start) * 1000, 2);
            $this->line("  ✓ Query executed in {$time}ms");
        } catch (\Exception $e) {
            $this->error("  ✗ Query failed: " . $e->getMessage());
            return 1;
        }
        $this->newLine();

        // Test 3: Show tables
        $this->info('Test 3: List Tables');
        try {
            $start = microtime(true);
            $tables = DB::select('SHOW TABLES');
            $time = round((microtime(true) - $start) * 1000, 2);
            $this->line("  ✓ Found " . count($tables) . " tables in {$time}ms");
            foreach ($tables as $table) {
                $tableName = array_values((array)$table)[0];
                $this->line("    - {$tableName}");
            }
        } catch (\Exception $e) {
            $this->error("  ✗ Failed to list tables: " . $e->getMessage());
            return 1;
        }
        $this->newLine();

        // Test 4: super_admins table
        $this->info('Test 4: Query super_admins Table');
        try {
            $start = microtime(true);
            $count = DB::table('super_admins')->count();
            $time = round((microtime(true) - $start) * 1000, 2);
            $this->line("  ✓ Found {$count} super admin(s) in {$time}ms");
            
            if ($count > 0) {
                $admins = DB::table('super_admins')->select('name', 'email')->get();
                foreach ($admins as $admin) {
                    $this->line("    - {$admin->name} ({$admin->email})");
                }
            }
        } catch (\Exception $e) {
            $this->error("  ✗ Failed to query super_admins: " . $e->getMessage());
            return 1;
        }
        $this->newLine();

        // Test 5: admin_users table
        $this->info('Test 5: Query admin_users Table');
        try {
            $start = microtime(true);
            $count = DB::table('admin_users')->count();
            $time = round((microtime(true) - $start) * 1000, 2);
            $this->line("  ✓ Found {$count} admin user(s) in {$time}ms");
            
            if ($count > 0) {
                $admins = DB::table('admin_users')->select('name', 'email')->get();
                foreach ($admins as $admin) {
                    $this->line("    - {$admin->name} ({$admin->email})");
                }
            }
        } catch (\Exception $e) {
            $this->error("  ✗ Failed to query admin_users: " . $e->getMessage());
            return 1;
        }
        $this->newLine();

        // Test 6: Test with retry
        $this->info('Test 6: Query with Retry Logic (simulating login)');
        $maxRetries = 3;
        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $start = microtime(true);
                $admin = DB::table('super_admins')
                    ->where('name', 'adminAlex')
                    ->orWhere('email', 'janarafael.sanandres@gmail.com')
                    ->first();
                $time = round((microtime(true) - $start) * 1000, 2);
                
                if ($admin) {
                    $this->line("  ✓ Found adminAlex in {$time}ms on attempt {$attempt}");
                } else {
                    $this->warn("  ! adminAlex not found (query took {$time}ms)");
                }
                break;
            } catch (\Exception $e) {
                $this->error("  ✗ Attempt {$attempt}/{$maxRetries} failed: " . $e->getMessage());
                if ($attempt < $maxRetries) {
                    $this->line("    Retrying in 1 second...");
                    sleep(1);
                    try {
                        DB::reconnect();
                    } catch (\Exception $reconnectException) {
                        $this->warn("    Reconnect failed: " . $reconnectException->getMessage());
                    }
                }
            }
        }
        $this->newLine();

        $this->info('✓ All tests completed successfully!');
        return 0;
    }
}
