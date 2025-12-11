<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin 1: admin1 / raf12345
        AdminUser::updateOrCreate([
            'name' => 'admin1',
        ], [
            'email' => 'admin1@scms.test',
            'password' => Hash::make('raf12345'),
        ]);

        // Admin 2: admin2 / dek12345
        AdminUser::updateOrCreate([
            'name' => 'admin2',
        ], [
            'email' => 'admin2@scms.test',
            'password' => Hash::make('dek12345'),
        ]);

        // Log success for debugging
        if ($this->command) {
            $this->command->info('AdminUsers seeded: admin1, admin2');
        }
    }
}
