<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SocialContractRecord;
use App\Models\SocialContractApproval;

class MigrateVerifiedRecordsToApprovals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'records:migrate-verified-to-approvals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate all existing verified records from social_contract_records to social_contract_approvals table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration of verified records to approvals table...');

        // Get all verified records that don't exist in approvals table yet
        $verifiedRecords = SocialContractRecord::where('status', 'Verified')
            ->with(['socialContract.student'])
            ->get();

        $this->info("Found {$verifiedRecords->count()} verified records.");

        $migratedCount = 0;
        $skippedCount = 0;

        foreach ($verifiedRecords as $record) {
            // Check if already exists in approvals
            $exists = SocialContractApproval::where('social_contract_record_id', $record->id)->exists();

            if ($exists) {
                $skippedCount++;
                continue;
            }

            $socialContract = $record->socialContract;
            
            if (!$socialContract || !$socialContract->student) {
                $this->warn("Skipping record ID {$record->id}: No student found.");
                $skippedCount++;
                continue;
            }

            $student = $socialContract->student;

            try {
                SocialContractApproval::create([
                    'social_contract_record_id' => $record->id,
                    'student_id' => $student->student_number ?? $student->id,
                    'student_name' => $student->name,
                    'event_name' => $record->event_name,
                    'organization' => $record->organization,
                    'venue' => $record->venue,
                    'hours_rendered' => $record->hours_rendered,
                    'date' => $record->date,
                    'status' => 'Verified',
                    'verified_at' => $record->updated_at,
                ]);

                $migratedCount++;
            } catch (\Exception $e) {
                $this->error("Error migrating record ID {$record->id}: {$e->getMessage()}");
                $skippedCount++;
            }
        }

        $this->info("Migration completed!");
        $this->info("Migrated: {$migratedCount} records");
        $this->info("Skipped: {$skippedCount} records");

        return Command::SUCCESS;
    }
}
