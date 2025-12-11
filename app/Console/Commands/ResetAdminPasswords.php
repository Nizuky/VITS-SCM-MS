<?php

namespace App\Console\Commands;

use App\Models\AdminUser;
use App\Models\SuperAdmin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetAdminPasswords extends Command
{
    protected $signature = 'admin:reset-passwords';
    protected $description = 'Reset all admin and super admin passwords to known values';

    public function handle()
    {
        $this->info('Resetting admin passwords...');
        $this->newLine();

        try {
            // Reset Super Admin password
            $this->info('Resetting Super Admin password...');
            $superAdmin = SuperAdmin::where('name', 'adminAlex')
                ->orWhere('email', 'janarafael.sanandres@gmail.com')
                ->first();
            
            if ($superAdmin) {
                $superAdmin->password = Hash::make('softdev12345');
                $superAdmin->save();
                $this->line("  ✓ Super Admin 'adminAlex' password reset to: softdev12345");
            } else {
                $this->warn("  ! Super Admin 'adminAlex' not found. Creating...");
                SuperAdmin::create([
                    'name' => 'adminAlex',
                    'email' => 'janarafael.sanandres@gmail.com',
                    'password' => Hash::make('softdev12345'),
                    'email_verified_at' => now(),
                ]);
                $this->line("  ✓ Super Admin 'adminAlex' created with password: softdev12345");
            }
            $this->newLine();

            // Reset Admin User 1
            $this->info('Resetting Admin User 1 password...');
            $admin1 = AdminUser::where('name', 'admin1')
                ->orWhere('email', 'admin1@scms.test')
                ->first();
            
            if ($admin1) {
                $admin1->password = Hash::make('raf12345');
                $admin1->save();
                $this->line("  ✓ Admin 'admin1' password reset to: raf12345");
            } else {
                $this->warn("  ! Admin 'admin1' not found. Creating...");
                AdminUser::create([
                    'name' => 'admin1',
                    'email' => 'admin1@scms.test',
                    'password' => Hash::make('raf12345'),
                ]);
                $this->line("  ✓ Admin 'admin1' created with password: raf12345");
            }
            $this->newLine();

            // Reset Admin User 2
            $this->info('Resetting Admin User 2 password...');
            $admin2 = AdminUser::where('name', 'admin2')
                ->orWhere('email', 'admin2@scms.test')
                ->first();
            
            if ($admin2) {
                $admin2->password = Hash::make('dek12345');
                $admin2->save();
                $this->line("  ✓ Admin 'admin2' password reset to: dek12345");
            } else {
                $this->warn("  ! Admin 'admin2' not found. Creating...");
                AdminUser::create([
                    'name' => 'admin2',
                    'email' => 'admin2@scms.test',
                    'password' => Hash::make('dek12345'),
                ]);
                $this->line("  ✓ Admin 'admin2' created with password: dek12345");
            }
            $this->newLine();

            $this->info('✓ All admin passwords have been reset successfully!');
            $this->newLine();
            $this->line('Credentials:');
            $this->line('  Super Admin: adminAlex / softdev12345');
            $this->line('  Admin 1: admin1 / raf12345');
            $this->line('  Admin 2: admin2 / dek12345');
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to reset passwords: ' . $e->getMessage());
            return 1;
        }
    }
}
