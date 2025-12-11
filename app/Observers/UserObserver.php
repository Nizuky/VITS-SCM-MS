<?php

namespace App\Observers;

use App\Models\User;
use App\Models\SocialContractApproval;
use App\Models\SupportTicket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the User "updated" event.
     * When a student's name or student_id changes, update all their records across the system:
     * - social_contract_approvals table
     * - support_tickets table
     */
    public function updated(User $user): void
    {
        $needsUpdate = false;
        $updateData = [];
        $changes = [];
        $tablesAffected = [];

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
                // Update social contract approvals
                $approvalCount = SocialContractApproval::where('student_id', $oldStudentId)
                    ->update(['student_id' => $newStudentId]);
                
                if ($approvalCount > 0) {
                    $tablesAffected[] = "social_contract_approvals ({$approvalCount} records)";
                }
                
                $changes['old_student_id'] = $oldStudentId;
                $changes['new_student_id'] = $newStudentId;
                
                Log::info('Student ID updated across system', [
                    'user_id' => $user->id,
                    'old_student_id' => $oldStudentId,
                    'new_student_id' => $newStudentId,
                    'tables_affected' => $tablesAffected,
                ]);
            }
        }

        // Update name if it changed
        if ($needsUpdate && $user->id) {
            // Update social contract approvals (use student_id if available, otherwise user id)
            $studentIdToMatch = $user->student_id ?: $user->id;
            
            $approvalCount = SocialContractApproval::where('student_id', $studentIdToMatch)
                ->update(['student_name' => $user->name]);
            
            if ($approvalCount > 0) {
                $tablesAffected[] = "social_contract_approvals ({$approvalCount} records)";
            }

            // Update support tickets (using the user id foreign key)
            $ticketCount = SupportTicket::where('student_id', $user->id)
                ->update(['student_name' => $user->name]);
            
            if ($ticketCount > 0) {
                $tablesAffected[] = "support_tickets ({$ticketCount} records)";
            }

            Log::info('Student name updated across system', [
                'user_id' => $user->id,
                'student_id' => $user->student_id,
                'changes' => $changes,
                'tables_affected' => $tablesAffected,
            ]);
        }
    }
}
