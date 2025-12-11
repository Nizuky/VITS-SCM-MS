<?php

namespace Database\Seeders\Dumps;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuperAdminsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('super_admins')->truncate();
        DB::table('super_admins')->insert(array (
  0 => 
  array (
    'id' => 2,
    'name' => 'adminAlex',
    'email' => 'janarafael.sanandres@gmail.com',
    'password' => '$2y$12$ccI8w9mN4AgKVm1gsTVME.6pv2Yakzn1ZKbSOBwOUuWSLH/9FD8xC',
    'email_verified_at' => '2025-10-12 14:00:04',
    'remember_token' => NULL,
    'created_at' => '2025-10-12 14:00:04',
    'updated_at' => '2025-12-11 00:00:00',
  ),
));
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
