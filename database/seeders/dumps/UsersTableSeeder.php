<?php

namespace Database\Seeders\Dumps;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::table('users')->insert(array (
  0 => 
  array (
    'id' => 3,
    'name' => 'Leila Sarte',
    'email' => 'leilanicolesarte@plv.edu.ph',
    'student_id' => '23-3171',
    'email_verified_at' => NULL,
    'password' => '$2y$12$.s5Vx4KtveV2uTngGpMLHu1zDZgO51t7lfPbAGIUFCVokypJSZDJa',
    'role' => 'student',
    'student_number' => NULL,
    'two_factor_secret' => NULL,
    'two_factor_recovery_codes' => NULL,
    'two_factor_confirmed_at' => NULL,
    'remember_token' => NULL,
    'created_at' => '2025-10-12 17:01:04',
    'updated_at' => '2025-10-12 17:01:04',
  ),
  1 => 
  array (
    'id' => 5,
    'name' => 'Jet Pagaduan',
    'email' => 'jetangelopagaduan@plv.edu.ph',
    'student_id' => '23-6969',
    'email_verified_at' => NULL,
    'password' => '$2y$12$9PkviPCHun3BfCMPm0rB6uxTU/jEVs4QRU5yVXBNY/HLZ3P0Rmthm',
    'role' => 'student',
    'student_number' => NULL,
    'two_factor_secret' => NULL,
    'two_factor_recovery_codes' => NULL,
    'two_factor_confirmed_at' => NULL,
    'remember_token' => NULL,
    'created_at' => '2025-10-13 13:33:48',
    'updated_at' => '2025-10-13 13:33:48',
  ),
  2 => 
  array (
    'id' => 6,
    'name' => 'Angel Dimatulac',
    'email' => 'angelcoleendimatulac@plv.edu.ph',
    'student_id' => '23-3371',
    'email_verified_at' => NULL,
    'password' => '$2y$12$2SrjxlGWWQYfnOVqG7ST1OFzu16VOV4Z5shjLGEzMufAbG/ecjRZ6',
    'role' => 'student',
    'student_number' => NULL,
    'two_factor_secret' => NULL,
    'two_factor_recovery_codes' => NULL,
    'two_factor_confirmed_at' => NULL,
    'remember_token' => 'BZrLbh4f3HWWXQjVPp8XKrc7w7rPiVp6sCXd6VqcjemHuAOpiRHVmX7hDJBy',
    'created_at' => '2025-10-13 14:07:12',
    'updated_at' => '2025-10-13 14:07:12',
  ),
  3 => 
  array (
    'id' => 13,
    'name' => 'Jan Rafael San Andres',
    'email' => 'janrafaelsanandres@plv.edu.ph',
    'student_id' => '23-3401',
    'email_verified_at' => '2025-10-13 17:51:02',
    'password' => '$2y$12$WdRxaG6Jmgo6nInoqxInq.B9XW.ZcaoS0kvEYt9/l4Ef2aZ8.75lO',
    'role' => 'student',
    'student_number' => NULL,
    'two_factor_secret' => NULL,
    'two_factor_recovery_codes' => NULL,
    'two_factor_confirmed_at' => NULL,
    'remember_token' => 'SwuAWkyvNJ95baQVKULIMa5HYf4Zr2L32Hxb1rVg7GMvXvGA833KXYCRfzWc',
    'created_at' => '2025-10-13 17:50:44',
    'updated_at' => '2025-10-13 17:51:02',
  ),
));
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
