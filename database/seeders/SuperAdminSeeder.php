<?php

namespace Database\Seeders;

use App\Models\SuperAdmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        SuperAdmin::updateOrCreate([
            'email' => 'janarafael.sanandres@gmail.com',
        ], [
            'name' => 'admin2025',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
        ]); 
    }
}
