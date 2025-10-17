<?php

namespace Database\Seeders\Dumps;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuperadminActivityLogsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('superadmin_activity_logs')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
