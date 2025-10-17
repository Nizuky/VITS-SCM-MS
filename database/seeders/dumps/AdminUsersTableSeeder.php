<?php

namespace Database\Seeders\Dumps;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminUsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('admin_users')->truncate();
        DB::table('admin_users')->insert(array (
  0 => 
  array (
    'id' => 1,
    'name' => 'admin1',
    'email' => 'janarafael.sanandres@gmail.com',
    'password' => '$2y$12$O5g6yLW1hpX.Y/Je2fgP2OiFT5H/L84gKw6KJE.pxE4sJtd9Gb4Um',
    'created_at' => '2025-10-13 03:20:23',
    'updated_at' => '2025-10-13 17:28:34',
  ),
  1 => 
  array (
    'id' => 2,
    'name' => 'admin2',
    'email' => 'janarafael.sanandres@gmail.com',
    'password' => '$2y$12$qhMotcVElaeUw0Igqwykuekxx9Rt5fhjNkdQikTaVlwQO8HKIGfAS',
    'created_at' => '2025-10-13 03:20:24',
    'updated_at' => '2025-10-13 13:07:05',
  ),
));
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
