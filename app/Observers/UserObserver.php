<?php

namespace App\Observers;

use App\Models\User;
use App\Models\SocialContractApproval;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the User "updated" event.
     * When a student's name or student_id changes, update all their records in social_contract_approvals
     */
    public function updated(User $user): void
    {
        $needsUpdate = false;
        $updateData = [];
        $changes = [];

        // Check if the name was changed
        if ($user->isDirty('name')) {
            $updateData['student_name'] = $user->name;
            $needsUpdate = true;
            $changes['old_name'] = $user->getOriginal('name');
            $changes['new_name'] = $user->name;
        }

        // Check if the student_id was changed
        if ($user->isDirty('student_id')) {
            $oldStudentId = $user->getOriginal('student_id');
            $newStudentId = $user->student_id;
            
            // Update all records with the old student_id to the new student_id
            if ($oldStudentId && $newStudentId) {
                SocialContractApproval::where('student_id', $oldStudentId)
                    ->update(['student_id' => $newStudentId]);
                
                $changes['old_student_id'] = $oldStudentId;
                $changes['new_student_id'] = $newStudentId;
                
                Log::info('Student ID updated in approvals', [
                    'user_id' => $user->id,
                    'old_student_id' => $oldStudentId,
                    'new_student_id' => $newStudentId,
                ]);
            }
        }

        // Update name if it changed (use the current student_id to find records)
        if ($needsUpdate && $user->student_id) {
            SocialContractApproval::where('student_id', $user->student_id)
                ->update($updateData);

            Log::info('Student name updated in approvals', [
                'user_id' => $user->id,
                'student_id' => $user->student_id,
                'changes' => $changes,
            ]);
        }
    }
}
