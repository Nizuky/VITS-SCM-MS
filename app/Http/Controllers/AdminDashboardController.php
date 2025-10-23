<?php

namespace App\Http\Controllers;

use App\Models\SocialContractRecord;
use App\Models\SocialContract;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
            $startOfMonth = $now->copy()->startOfMonth(); // First day of current month
            $endOfMonth = $now->copy()->endOfMonth(); // Last day of current month

            // Count pending requests (all time)
            $pendingCount = SocialContractRecord::where('status', 'Pending')->count();

            // Count verified (accepted) this month - using updated_at since that's when status changed
            $verifiedThisMonth = SocialContractRecord::where('status', 'Verified')
                ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
                ->count();

            // Count rejected this month
            $rejectedThisMonth = SocialContractRecord::where('status', 'Rejected')
                ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
                ->count();

            \Log::info('Dashboard Stats', [
                'pending' => $pendingCount,
                'verified_this_month' => $verifiedThisMonth,
                'rejected_this_month' => $rejectedThisMonth,
                'start_of_month' => $startOfMonth->toDateTimeString(),
                'end_of_month' => $endOfMonth->toDateTimeString(),
                'now' => $now->toDateTimeString()
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'pending' => $pendingCount,
                    'verified_this_week' => $verifiedThisMonth, // Keep same key for frontend compatibility
                    'rejected_this_week' => $rejectedThisMonth  // Keep same key for frontend compatibility
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
    public function rejectSubmission(Request $request, $id)
    {
        try {
            // Validate the rejection reason
            $validated = $request->validate([
                'reason' => 'required|string|min:3|max:1000'
            ]);
            
            DB::beginTransaction();
            
            $record = SocialContractRecord::findOrFail($id);
            $oldStatus = $record->status;
            
            // Update status and rejection reason
            $record->status = 'Rejected';
            $record->rejection_reason = $validated['reason'];
            $record->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Submission rejected successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a valid rejection reason (minimum 3 characters).'
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject submission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get activity calendar data for a specific year
     */
    public function getActivityCalendar(Request $request)
    {
        try {
            $year = $request->input('year', Carbon::now()->year);
            
            \Log::info('Admin activity calendar requested', ['year' => $year]);
            
            // Validate year
            $year = (int)$year;
            $currentYear = Carbon::now()->year;
            if ($year > $currentYear) {
                $year = $currentYear;
            }
            
            $startDate = Carbon::create($year, 1, 1)->startOfDay();
            $endDate = Carbon::create($year, 12, 31)->endOfDay();

            // Get all verified and rejected records (admin actions) in the date range
            // Note: Admin only does verified and rejected, NO approved
            $activities = DB::table('social_contract_records')
                ->select(
                    DB::raw('DATE(updated_at) as date'),
                    DB::raw('COUNT(*) as count')
                )
                ->whereIn('status', ['Verified', 'Rejected'])
                ->whereBetween('updated_at', [$startDate, $endDate])
                ->groupBy(DB::raw('DATE(updated_at)'))
                ->get();

            // Transform to array with date as key
            $activityData = [];
            foreach ($activities as $activity) {
                $activityData[$activity->date] = $activity->count;
            }

            \Log::info('Admin activity calendar data', ['count' => count($activityData), 'year' => $year]);

            return response()->json([
                'success' => true,
                'data' => $activityData,
                'year' => $year
            ]);
        } catch (\Exception $e) {
            \Log::error('Admin activity calendar error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch activity data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get activity details for a specific date
     */
    public function getActivityDetails(Request $request)
    {
        try {
            $date = $request->input('date');
            
            if (!$date) {
                return response()->json([
                    'success' => false,
                    'message' => 'Date parameter is required'
                ], 400);
            }
            
            $startOfDay = Carbon::parse($date)->startOfDay();
            $endOfDay = Carbon::parse($date)->endOfDay();
            
            // Get current admin's name
            $currentAdminName = Auth::guard('admin')->user()->name;
            
            // Get all admin activities for this date (verified and rejected only)
            $activities = DB::table('social_contract_records as scr')
                ->join('social_contracts as sc', 'scr.social_contract_id', '=', 'sc.id')
                ->join('users as u', 'sc.student_id', '=', 'u.id')
                ->select(
                    'scr.id',
                    'scr.status as action',
                    'scr.event_name',
                    'scr.venue',
                    'scr.updated_at as created_at',
                    'u.student_id',
                    'u.name as student_name'
                )
                ->whereIn('scr.status', ['Verified', 'Rejected'])
                ->whereBetween('scr.updated_at', [$startOfDay, $endOfDay])
                ->orderBy('scr.updated_at', 'desc')
                ->get()
                ->map(function($record) use ($currentAdminName) {
                    $actionText = ucfirst(strtolower($record->action));
                    $description = "{$actionText} submission for {$record->event_name}";
                    
                    return [
                        'id' => $record->id,
                        'action' => strtolower($record->action), // 'verified' or 'rejected'
                        'description' => $description,
                        'created_at' => Carbon::parse($record->created_at)->toIso8601String(),
                        'student_id' => $record->student_id,
                        'student_name' => $record->student_name,
                        'venue' => $record->venue,
                        'admin_name' => $currentAdminName
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => $activities,
                'date' => $date
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to get admin activity details', [
                'error' => $e->getMessage(),
                'date' => $request->input('date')
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load activity details'
            ], 500);
        }
    }
}
