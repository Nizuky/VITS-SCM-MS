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
    'name' => 'admin2025',
    'email' => 'janarafael.sanandres@gmail.com',
    'password' => '$2y$12$3PuR/ubI2uYFv0l7efghyOTYa/zqXeaPycT80y6wipFUN2SM7mhMe',
    'email_verified_at' => '2025-10-12 14:00:04',
    'remember_token' => NULL,
    'created_at' => '2025-10-12 14:00:04',
    'updated_at' => '2025-10-12 14:00:04',
  ),
));
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
