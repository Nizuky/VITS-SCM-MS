<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $env = env('APP_ENV', 'production');

        $email = env('ADMIN_EMAIL', 'admin@scms.test');
        $password = env('ADMIN_PASSWORD', 'admin123456');
        $name = env('ADMIN_NAME', 'Site Admin');

        if ($env === 'production' && (empty(env('ADMIN_EMAIL')) || empty(env('ADMIN_PASSWORD')))) {
            if ($this->command) {
                $this->command->info('ADMIN_EMAIL or ADMIN_PASSWORD not set — skipping AdminUser seeding for production.');
            }
            return;
        }

        AdminUser::updateOrCreate([
            'email' => $email,
        ], [
            'name' => $name,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ]);
    }
}
