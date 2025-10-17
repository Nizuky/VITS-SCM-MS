<?php

namespace Database\Seeders\Dumps;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('jobs')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
