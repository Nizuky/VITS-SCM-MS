<?php

namespace App\Console\Commands;

use App\Models\SuperAdmin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ForceUpdateSuperAdmin extends Command
{
    protected $signature = 'superadmin:force-update';
    protected $description = 'Force update super admin credentials to adminAlex/softdev2025';

    public function handle()
    {
        $this->info('Force updating super admin credentials...');
        $this->newLine();
        
        try {
            // Disable foreign key checks
            \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            // Delete all existing super admins
            $deleted = SuperAdmin::count();
            SuperAdmin::truncate();
            $this->warn("Deleted {$deleted} existing super admin(s)");
            
            // Re-enable foreign key checks
            \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            // Create new super admin with exact credentials
            $admin = SuperAdmin::create([
                'name' => 'adminAlex',
                'email' => 'janarafael.sanandres@gmail.com',
                'password' => Hash::make('softdev2025'),
                'email_verified_at' => now(),
            ]);
            
            $this->newLine();
            $this->info('✓ Super admin created successfully!');
            $this->line("  ID: {$admin->id}");
            $this->line("  Name: {$admin->name}");
            $this->line("  Email: {$admin->email}");
            $this->line("  Password: softdev2025");
            $this->newLine();
            
            // Test the password
            if (Hash::check('softdev2025', $admin->password)) {
                $this->info('✓ Password verification PASSED!');
            } else {
                $this->error('✗ Password verification FAILED!');
                return 1;
            }
            
            $this->newLine();
            $this->info('Super admin is ready to login with:');
            $this->line('  Username: adminAlex');
            $this->line('  Password: softdev2025');
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Failed to update super admin: ' . $e->getMessage());
            return 1;
        }
    }
}
