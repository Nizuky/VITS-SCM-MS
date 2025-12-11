<?php

namespace Database\Seeders;

use App\Models\SuperAdmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create the Super Admin account
        // Uses environment variables if set, otherwise defaults
        $email = env('SUPERADMIN_EMAIL', 'janarafael.sanandres@gmail.com');
        $password = env('SUPERADMIN_PASSWORD', 'softdev2025');
        $name = env('SUPERADMIN_NAME', 'adminAlex');

        SuperAdmin::updateOrCreate([
            'email' => $email,
        ], [
            'name' => $name,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ]);

        // Log success for debugging
        if ($this->command) {
            $this->command->info("SuperAdmin seeded: {$name} ({$email})");
        }
    }
}
