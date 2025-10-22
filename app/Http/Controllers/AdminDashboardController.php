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
     * Get dashboard statistics
     */
    public function getDashboardStats()
    {
        try {
            $now = Carbon::now();
            $weekAgo = $now->copy()->subDays(7);

            // Count pending requests (all time)
            $pendingCount = SocialContractRecord::where('status', 'Pending')->count();

            // Count verified (accepted) this week - using updated_at since that's when status changed
            $verifiedThisWeek = SocialContractRecord::where('status', 'Verified')
                ->where('updated_at', '>=', $weekAgo)
                ->count();

            // Count rejected this week
            $rejectedThisWeek = SocialContractRecord::where('status', 'Rejected')
                ->where('updated_at', '>=', $weekAgo)
                ->count();

            \Log::info('Dashboard Stats', [
                'pending' => $pendingCount,
                'verified_this_week' => $verifiedThisWeek,
                'rejected_this_week' => $rejectedThisWeek,
                'week_ago' => $weekAgo->toDateTimeString(),
                'now' => $now->toDateTimeString()
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'pending' => $pendingCount,
                    'verified_this_week' => $verifiedThisWeek,
                    'rejected_this_week' => $rejectedThisWeek
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch dashboard stats: ' . $e->getMessage()
            ], 500);
        }
    }

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
            \Log::error('Failed to fetch admin submissions', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
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
            
            // Observer will automatically create approval record in social_contract_approvals table
            
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
