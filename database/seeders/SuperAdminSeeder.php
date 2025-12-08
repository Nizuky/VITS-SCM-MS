<?php

namespace Database\Seeders;

use App\Models\SuperAdmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $env = env('APP_ENV', 'production');

        $email = env('SUPERADMIN_EMAIL', 'janarafael.sanandres@gmail.com');
        $password = env('SUPERADMIN_PASSWORD', 'softdev2025');
        $name = env('SUPERADMIN_NAME', 'Super Admin');

        if ($env === 'production' && (empty(env('SUPERADMIN_EMAIL')) || empty(env('SUPERADMIN_PASSWORD')))) {
            // In production we require credentials to be provided via environment variables.
            if ($this->command) {
                $this->command->info('SUPERADMIN_EMAIL or SUPERADMIN_PASSWORD not set — skipping SuperAdmin seeding for production.');
            }
            return;
        }

        SuperAdmin::updateOrCreate([
            'email' => $email,
        ], [
            'name' => $name,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ]);
    }
}
