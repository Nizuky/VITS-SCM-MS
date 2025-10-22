<?php

namespace Database\Seeders\Dumps;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SocialContractRecordsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('social_contract_records')->truncate();
        
        // Insert social_contract_records (social_contracts must be seeded first)
        $records = [
            // Leila Sarte's records (social_contract_id = 1)
            [
                'social_contract_id' => 1,
                'date' => Carbon::now()->subDays(8)->format('Y-m-d'),
                'event_name' => 'Community Clean-up Drive',
                'venue' => 'Barangay Hall, District 1',
                'organization' => 'Barangay Council',
                'hours_rendered' => 8,
                'status' => 'Verified',
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(3), // Verified 3 days ago
            ],
            [
                'social_contract_id' => 1,
                'date' => Carbon::now()->subDays(12)->format('Y-m-d'),
                'event_name' => 'Tree Planting Activity',
                'venue' => 'PLV Campus Grounds',
                'organization' => 'Environmental Club',
                'hours_rendered' => 6,
                'status' => 'Verified',
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(2), // Verified 2 days ago
            ],
            [
                'social_contract_id' => 1,
                'date' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'event_name' => 'Feeding Program',
                'venue' => 'Elementary School',
                'organization' => 'Lions Club',
                'hours_rendered' => 5,
                'status' => 'Pending',
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],

            // Jet Pagaduan's records (social_contract_id = 2)
            [
                'social_contract_id' => 2,
                'date' => Carbon::now()->subDays(15)->format('Y-m-d'),
                'event_name' => 'Blood Donation Drive',
                'venue' => 'Red Cross Center',
                'organization' => 'Philippine Red Cross',
                'hours_rendered' => 4,
                'status' => 'Verified',
                'created_at' => Carbon::now()->subDays(12),
                'updated_at' => Carbon::now()->subDays(5), // Verified 5 days ago
            ],
            [
                'social_contract_id' => 2,
                'date' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'event_name' => 'Outreach Program',
                'venue' => 'Orphanage Home',
                'organization' => 'Student Council',
                'hours_rendered' => 10,
                'status' => 'Verified',
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(1), // Verified 1 day ago
            ],
            [
                'social_contract_id' => 2,
                'date' => Carbon::now()->subDays(7)->format('Y-m-d'),
                'event_name' => 'Medical Mission',
                'venue' => 'Community Center',
                'organization' => 'Health Department',
                'hours_rendered' => 7,
                'status' => 'Pending',
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(8),
            ],

            // Angel Dimatulac's records (social_contract_id = 3)
            [
                'social_contract_id' => 3,
                'date' => Carbon::now()->subDays(14)->format('Y-m-d'),
                'event_name' => 'Tutorial Program',
                'venue' => 'Public Library',
                'organization' => 'Education Foundation',
                'hours_rendered' => 12,
                'status' => 'Verified',
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(4), // Verified 4 days ago
            ],
            [
                'social_contract_id' => 3,
                'date' => Carbon::now()->subDays(9)->format('Y-m-d'),
                'event_name' => 'Disaster Preparedness Seminar',
                'venue' => 'City Hall',
                'organization' => 'NDRRMC',
                'hours_rendered' => 6,
                'status' => 'Rejected',
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(2), // Rejected 2 days ago
            ],
            [
                'social_contract_id' => 3,
                'date' => Carbon::now()->subDays(4)->format('Y-m-d'),
                'event_name' => 'Sports Clinic',
                'venue' => 'Sports Complex',
                'organization' => 'Athletics Department',
                'hours_rendered' => 8,
                'status' => 'Pending',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],

            // Jan Rafael San Andres's records (social_contract_id = 4)
            [
                'social_contract_id' => 4,
                'date' => Carbon::now()->subDays(11)->format('Y-m-d'),
                'event_name' => 'Coastal Cleanup',
                'venue' => 'Manila Bay',
                'organization' => 'Ocean Warriors',
                'hours_rendered' => 9,
                'status' => 'Verified',
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(6), // Verified 6 days ago
            ],
            [
                'social_contract_id' => 4,
                'date' => Carbon::now()->subDays(9)->format('Y-m-d'),
                'event_name' => 'Book Drive',
                'venue' => 'School Campus',
                'organization' => 'Library Committee',
                'hours_rendered' => 5,
                'status' => 'Rejected',
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(4), // Rejected 4 days ago
            ],
            [
                'social_contract_id' => 4,
                'date' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'event_name' => 'Cultural Festival Volunteer',
                'venue' => 'City Plaza',
                'organization' => 'Cultural Affairs',
                'hours_rendered' => 10,
                'status' => 'Pending',
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'social_contract_id' => 4,
                'date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'event_name' => 'Youth Leadership Summit',
                'venue' => 'Convention Center',
                'organization' => 'Youth Council',
                'hours_rendered' => 6,
                'status' => 'Pending',
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
        ];

        DB::table('social_contract_records')->insert($records);
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
