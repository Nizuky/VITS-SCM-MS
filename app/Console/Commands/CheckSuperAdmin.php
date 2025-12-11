<?php

namespace App\Console\Commands;

use App\Models\SuperAdmin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CheckSuperAdmin extends Command
{
    protected $signature = 'superadmin:check {name=adminAlex}';
    protected $description = 'Check if super admin account exists and verify password';

    public function handle()
    {
        $name = $this->argument('name');
        
        $this->info("Checking for super admin: {$name}");
        $this->newLine();
        
        try {
            // Test database connection
            $this->info('Testing database connection...');
            $dbName = DB::connection()->getDatabaseName();
            $this->info("✓ Connected to database: {$dbName}");
            $this->newLine();
            
            // Check if table exists
            $this->info('Checking if super_admins table exists...');
            if (!DB::getSchemaBuilder()->hasTable('super_admins')) {
                $this->error('✗ Table super_admins does NOT exist!');
                $this->warn('Run: php artisan migrate --force');
                return 1;
            }
            $this->info('✓ Table super_admins exists');
            $this->newLine();
            
            // Count total super admins
            $total = SuperAdmin::count();
            $this->info("Total super admins in database: {$total}");
            $this->newLine();
            
            // List all super admins
            if ($total > 0) {
                $this->info('All super admin accounts:');
                $admins = SuperAdmin::all();
                foreach ($admins as $admin) {
                    $this->line("  ID: {$admin->id} | Name: {$admin->name} | Email: {$admin->email}");
                }
                $this->newLine();
            }
            
            // Check specific admin
            $admin = SuperAdmin::where('name', $name)
                ->orWhere('email', 'janarafael.sanandres@gmail.com')
                ->first();
            
            if (!$admin) {
                $this->error("✗ Super admin '{$name}' NOT found!");
                $this->warn('Run seeders: php artisan db:seed --class=SuperAdminSeeder --force');
                return 1;
            }
            
            $this->info("✓ Found super admin:");
            $this->line("  ID: {$admin->id}");
            $this->line("  Name: {$admin->name}");
            $this->line("  Email: {$admin->email}");
            $this->line("  Email Verified: " . ($admin->email_verified_at ? 'Yes' : 'No'));
            $this->newLine();
            
            // Test password
            $testPassword = 'softdev2025';
            $this->info("Testing password '{$testPassword}'...");
            
            if (Hash::check($testPassword, $admin->password)) {
                $this->info("✓ Password '{$testPassword}' is CORRECT!");
            } else {
                $this->error("✗ Password '{$testPassword}' is INCORRECT!");
                $this->warn("Current password hash: {$admin->password}");
                $this->warn('Reset password: php artisan admin:reset-passwords');
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Database error: ' . $e->getMessage());
            return 1;
        }
    }
}
