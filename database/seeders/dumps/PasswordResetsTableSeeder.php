<?php

namespace Database\Seeders\Dumps;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PasswordResetsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('password_resets')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
