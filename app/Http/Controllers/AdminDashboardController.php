<?php

namespace App\Http\Controllers;

use App\Models\SocialContractRecord;
use App\Models\SocialContract;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Get all submissions with student information
     */
    public function getSubmissions()
    {
        try {
            $submissions = DB::table('social_contract_records as scr')
                ->join('social_contracts as sc', 'scr.social_contract_id', '=', 'sc.id')
                ->join('users as u', 'sc.student_id', '=', 'u.id')
                ->select(
                    'scr.id',
                    'u.student_id',
                    'u.name as student_name',
                    'scr.event_name',
                    'scr.organization',
                    'scr.hours_rendered',
                    'scr.date',
                    'scr.status',
                    'scr.venue',
                    'scr.created_at'
                )
                ->orderBy('scr.created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $submissions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch submissions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify a submission
     */
    public function verifySubmission($id)
    {
        try {
            DB::beginTransaction();
            
            $record = SocialContractRecord::findOrFail($id);
            $oldStatus = $record->status;
            
            // Update status
            $record->status = 'Verified';
            $record->save();
            
            // Log verification
            \App\Models\SocialContractRecordVerification::create([
                'social_contract_record_id' => $record->id,
                'verified_by' => auth('admin')->id(),
                'verified_at' => now(),
                'verification_notes' => 'Verified by admin'
            ]);
            
            // Log status change
            \App\Models\SocialContractRecordStatusHistory::create([
                'social_contract_record_id' => $record->id,
                'old_status' => $oldStatus,
                'new_status' => 'Verified',
                'changed_by' => auth('admin')->id(),
                'changed_at' => now(),
                'change_reason' => 'Record verified by admin'
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Submission verified successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify submission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a submission
     */
    public function rejectSubmission($id)
    {
        try {
            DB::beginTransaction();
            
            $record = SocialContractRecord::findOrFail($id);
            $oldStatus = $record->status;
            
            // Update status
            $record->status = 'Rejected';
            $record->save();
            
            // Log rejection
            \App\Models\SocialContractRecordRejection::create([
                'social_contract_record_id' => $record->id,
                'rejected_by' => auth('admin')->id(),
                'rejected_at' => now(),
                'rejection_reason' => 'Rejected by admin',
                'rejection_notes' => null
            ]);
            
            // Log status change
            \App\Models\SocialContractRecordStatusHistory::create([
                'social_contract_record_id' => $record->id,
                'old_status' => $oldStatus,
                'new_status' => 'Rejected',
                'changed_by' => auth('admin')->id(),
                'changed_at' => now(),
                'change_reason' => 'Record rejected by admin'
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Submission rejected successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject submission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get activity calendar data for the last 365 days
     */
    public function getActivityCalendar()
    {
        try {
            $endDate = Carbon::now();
            $startDate = $endDate->copy()->subDays(364);

            // Get all verified and rejected records (admin actions) in the date range
            $activities = DB::table('social_contract_records')
                ->select(
                    DB::raw('DATE(updated_at) as date'),
                    DB::raw('COUNT(*) as count')
                )
                ->whereIn('status', ['Verified', 'Rejected'])
                ->whereBetween('updated_at', [$startDate, $endDate])
                ->groupBy('date')
                ->get();

            // Transform to array with date as key
            $activityData = [];
            foreach ($activities as $activity) {
                $activityData[$activity->date] = $activity->count;
            }

            return response()->json([
                'success' => true,
                'data' => $activityData,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch activity data: ' . $e->getMessage()
            ], 500);
        }
    }
}
