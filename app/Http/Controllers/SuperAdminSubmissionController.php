<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SocialContractRecord;
use App\Models\SocialContractApproval;

class SuperAdminSubmissionController extends Controller
{
    /**
     * Get all submissions for super admin
     * Pending: from social_contract_records with status 'Pending'
     * For Approval: from social_contract_approvals with status 'Verified'
     * Archived: from social_contract_approvals with status 'Approved' or 'Rejected'
     */
    public function index()
    {
        try {
            // Get pending records from social_contract_records
            $pendingRecords = SocialContractRecord::where('status', 'Pending')
                ->with(['socialContract.student'])
                ->get()
                ->map(function ($record) {
                    $student = $record->socialContract->student ?? null;
                    return [
                        'id' => $record->id,
                        'student_id' => $student->student_number ?? $student->id ?? '',
                        'student_name' => $student->name ?? '',
                        'event_name' => $record->event_name,
                        'organization' => $record->organization,
                        'venue' => $record->venue,
                        'hours_rendered' => $record->hours_rendered,
                        'date' => $record->date->format('Y-m-d'),
                        'status' => 'Pending',
                    ];
                });

            // Get verified records (for approval) from social_contract_approvals
            $forApprovalRecords = SocialContractApproval::where('status', 'Verified')
                ->get()
                ->map(function ($approval) {
                    return [
                        'id' => $approval->id,
                        'record_id' => $approval->social_contract_record_id,
                        'student_id' => $approval->student_id,
                        'student_name' => $approval->student_name,
                        'event_name' => $approval->event_name,
                        'organization' => $approval->organization,
                        'venue' => $approval->venue,
                        'hours_rendered' => $approval->hours_rendered,
                        'date' => $approval->date->format('Y-m-d'),
                        'status' => 'Verified',
                    ];
                });

            // Get archived records (approved/rejected) from social_contract_approvals
            $archivedRecords = SocialContractApproval::whereIn('status', ['Approved', 'Rejected'])
                ->get()
                ->map(function ($approval) {
                    return [
                        'id' => $approval->id,
                        'record_id' => $approval->social_contract_record_id,
                        'student_id' => $approval->student_id,
                        'student_name' => $approval->student_name,
                        'event_name' => $approval->event_name,
                        'organization' => $approval->organization,
                        'venue' => $approval->venue,
                        'hours_rendered' => $approval->hours_rendered,
                        'date' => $approval->date->format('Y-m-d'),
                        'status' => $approval->status,
                        'rejection_reason' => $approval->rejection_reason,
                    ];
                });

            // Merge all records
            $allSubmissions = $pendingRecords
                ->concat($forApprovalRecords)
                ->concat($archivedRecords);

            return response()->json([
                'success' => true,
                'submissions' => $allSubmissions,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load submissions: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a record permanently from database and social contract
     * Only accessible by super admin
     */
    public function destroy($id)
    {
        try {
            // Find the record in social_contract_records table
            $record = SocialContractRecord::findOrFail($id);
            
            // Get the social contract ID before deleting
            $socialContractId = $record->social_contract_id;
            
            // Delete the approval record if it exists (cascade will handle this but be explicit)
            SocialContractApproval::where('social_contract_record_id', $id)->delete();
            
            // Delete the record itself
            $record->delete();
            
            // Note: The social contract itself remains (the student's contract)
            // We're only deleting the specific record entry
            
            return response()->json([
                'success' => true,
                'message' => 'Record deleted successfully'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error deleting record: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete record: ' . $e->getMessage()
            ], 500);
        }
    }
}
