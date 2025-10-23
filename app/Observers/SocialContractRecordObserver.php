<?php

namespace App\Observers;

use App\Models\SocialContractRecord;
use App\Models\SocialContractApproval;

class SocialContractRecordObserver
{
    /**
     * Handle the SocialContractRecord "updated" event.
     * When a record status changes to "Verified", copy it to approvals table
     */
    public function updated(SocialContractRecord $socialContractRecord): void
    {
        // Check if status was changed to "Verified"
        if ($socialContractRecord->isDirty('status') && $socialContractRecord->status === 'Verified') {
            // Get student information from the social contract
            $socialContract = $socialContractRecord->socialContract()->with('student')->first();
            
            if ($socialContract && $socialContract->student) {
                $student = $socialContract->student;
                
                // Check if this record already exists in approvals table
                $existingApproval = SocialContractApproval::where('social_contract_record_id', $socialContractRecord->id)->first();
                
                if (!$existingApproval) {
                    // Create new approval record
                    SocialContractApproval::create([
                        'social_contract_record_id' => $socialContractRecord->id,
                        'student_id' => $student->student_id ?? 'N/A',
                        'student_name' => $student->name,
                        'event_name' => $socialContractRecord->event_name,
                        'organization' => $socialContractRecord->organization,
                        'venue' => $socialContractRecord->venue,
                        'hours_rendered' => $socialContractRecord->hours_rendered,
                        'date' => $socialContractRecord->date,
                        'status' => 'Verified',
                        'verified_by' => auth()->guard('admin')->id() ?? null,
                        'verified_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Handle the SocialContractRecord "deleted" event.
     */
    public function deleted(SocialContractRecord $socialContractRecord): void
    {
        // If the record is deleted, also delete from approvals table
        SocialContractApproval::where('social_contract_record_id', $socialContractRecord->id)->delete();
    }
}
