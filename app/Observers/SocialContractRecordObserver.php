<?php

namespace App\Observers;

use App\Models\SocialContractRecord;
use App\Models\SocialContractApproval;

/**
 * Observer for SocialContractRecord model
 * 
 * CRITICAL WORKFLOW:
 * When admin verifies a submission (status changes to "Verified"):
 * 1. This observer automatically creates a record in social_contract_approvals table
 * 2. The approval record contains all submission details + verified_at timestamp
 * 3. Super admin then works with records from social_contract_approvals table
 * 4. This ensures data consistency between submissions and approvals
 * 
 * DO NOT REMOVE THIS OBSERVER - It's essential for the approval workflow!
 */
class SocialContractRecordObserver
{
    /**
     * Handle the SocialContractRecord "updated" event.
     * When a record status changes to "Verified", copy it to approvals table
     */
    public function updated(SocialContractRecord $socialContractRecord): void
    {
        \Log::info('SocialContractRecordObserver::updated called', [
            'record_id' => $socialContractRecord->id,
            'status' => $socialContractRecord->status,
            'isDirty_status' => $socialContractRecord->isDirty('status'),
            'all_dirty' => $socialContractRecord->getDirty(),
        ]);
        
        // Check if status was changed to "Verified"
        if ($socialContractRecord->isDirty('status') && $socialContractRecord->status === 'Verified') {
            \Log::info('Status changed to Verified, creating approval record', [
                'record_id' => $socialContractRecord->id
            ]);
            
            // Get student information from the social contract
            $socialContract = $socialContractRecord->socialContract()->with('student')->first();
            
            if ($socialContract && $socialContract->student) {
                $student = $socialContract->student;
                
                \Log::info('Found student for approval', [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name
                ]);
                
                // Check if this record already exists in approvals table
                $existingApproval = SocialContractApproval::where('social_contract_record_id', $socialContractRecord->id)->first();
                
                if (!$existingApproval) {
                    // Create new approval record
                    $approval = SocialContractApproval::create([
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
                    
                    \Log::info('Approval record created successfully', [
                        'approval_id' => $approval->id,
                        'social_contract_record_id' => $socialContractRecord->id,
                        'status' => $approval->status
                    ]);
                } else {
                    \Log::warning('Approval record already exists', [
                        'approval_id' => $existingApproval->id,
                        'record_id' => $socialContractRecord->id
                    ]);
                }
            } else {
                \Log::error('Could not find student for approval creation', [
                    'record_id' => $socialContractRecord->id,
                    'has_social_contract' => $socialContract !== null,
                    'has_student' => $socialContract && $socialContract->student !== null
                ]);
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
