<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        AdminUser::updateOrCreate([
            'email' => 'admin@scms.test',
        ], [
            'name' => 'Site Admin',
            'password' => Hash::make('admin123456'),
            'email_verified_at' => now(),
        ]);
    }
}
