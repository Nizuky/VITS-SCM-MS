<?php

namespace App\Console\Commands;

use App\Models\SocialContractApproval;
use App\Models\SocialContractRecord;
use Illuminate\Console\Command;

class FixApprovalStudentIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:approval-student-ids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix student_id values in social_contract_approvals table to use actual student_id instead of user id';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing student_id values in social_contract_approvals table...');
        
        $approvals = SocialContractApproval::all();
        $fixed = 0;
        $skipped = 0;
        
        foreach ($approvals as $approval) {
            // Get the related social contract record
            $record = SocialContractRecord::find($approval->social_contract_record_id);
            
            if ($record && $record->socialContract && $record->socialContract->student) {
                $student = $record->socialContract->student;
                $correctStudentId = $student->student_id;
                
                // Check if student_id is numeric (likely a user ID instead of student_id)
                if ($correctStudentId && $approval->student_id !== $correctStudentId) {
                    $this->info("Updating approval ID {$approval->id}: {$approval->student_id} → {$correctStudentId}");
                    $approval->student_id = $correctStudentId;
                    $approval->save();
                    $fixed++;
                } else {
                    $skipped++;
                }
            } else {
                $this->warn("Could not find student for approval ID {$approval->id}");
                $skipped++;
            }
        }
        
        $this->info("Fixed {$fixed} records, skipped {$skipped} records.");
        $this->info('Done!');
        
        return 0;
    }
}
