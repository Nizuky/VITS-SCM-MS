<?php

namespace Database\Seeders\Dumps;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SocialContractsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('social_contracts')->truncate();
        
        // Create social_contracts entries for each student
        $socialContracts = [
            [
                'id' => 1,
                'student_id' => 3, // Leila Sarte
                'submission_date' => Carbon::now()->subDays(30),
                'status' => 'submitted',
                'created_at' => Carbon::now()->subDays(30),
                'updated_at' => Carbon::now()->subDays(30),
            ],
            [
                'id' => 2,
                'student_id' => 5, // Jet Pagaduan
                'submission_date' => Carbon::now()->subDays(25),
                'status' => 'submitted',
                'created_at' => Carbon::now()->subDays(25),
                'updated_at' => Carbon::now()->subDays(25),
            ],
            [
                'id' => 3,
                'student_id' => 6, // Angel Dimatulac
                'submission_date' => Carbon::now()->subDays(20),
                'status' => 'submitted',
                'created_at' => Carbon::now()->subDays(20),
                'updated_at' => Carbon::now()->subDays(20),
            ],
            [
                'id' => 4,
                'student_id' => 13, // Jan Rafael San Andres
                'submission_date' => Carbon::now()->subDays(15),
                'status' => 'submitted',
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(15),
            ],
        ];

        DB::table('social_contracts')->insert($socialContracts);
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
