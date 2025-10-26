<?php

namespace App\Http\Controllers;

use App\Models\SocialContractRecord;
use App\Models\SocialContractApproval;
use App\Models\SocialContract;
use App\Models\User;
use App\Models\StudentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
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
            \Log::info('AdminDashboardController@getSubmissions called');
            
            // Log table counts for debugging
            $pendingCount = SocialContractRecord::where('status', 'Pending')->count();
            $verifiedInRecordsCount = SocialContractRecord::where('status', 'Verified')->count();
            $verifiedInApprovalsCount = SocialContractApproval::where('status', 'Verified')->count();
            $approvedCount = SocialContractApproval::where('status', 'Approved')->count();
            $rejectedCount = SocialContractApproval::where('status', 'Rejected')->count();
            
            \Log::info('Admin getSubmissions - Table counts', [
                'pending_in_records' => $pendingCount,
                'verified_in_records' => $verifiedInRecordsCount,
                'verified_in_approvals' => $verifiedInApprovalsCount,
                'approved_in_approvals' => $approvedCount,
                'rejected_in_approvals' => $rejectedCount,
            ]);
            
            $allSubmissions = collect();
            
            // Get pending records from social_contract_records
            $pendingRecords = SocialContractRecord::where('status', 'Pending')
                ->with(['socialContract.student'])
                ->orderBy('updated_at', 'desc')
                ->get()
                ->map(function ($record) {
                    $student = $record->socialContract->student ?? null;
                    $dateFormatted = null;
                    if ($record->date) {
                        try {
                            $dateObj = $record->date instanceof \Carbon\Carbon ? $record->date : Carbon::parse($record->date);
                            $dateFormatted = $dateObj->format('Y-m-d');
                        } catch (\Exception $e) {
                            $dateFormatted = $record->date;
                        }
                    }
                    
                    return [
                        'id' => $record->id,
                        'student_id' => $student ? ($student->student_id ?? 'N/A') : 'N/A',
                        'student_name' => $student->name ?? '',
                        'event_name' => $record->event_name,
                        'organization' => $record->organization,
                        'venue' => $record->venue,
                        'hours_rendered' => $record->hours_rendered,
                        'date' => $dateFormatted,
                        'status' => 'Pending',
                        'created_at' => $record->created_at->toIso8601String(),
                        'updated_at' => $record->updated_at->toIso8601String(),
                    ];
                });
            
            // Get archived records from social_contract_approvals (Verified, Approved, Rejected)
            $archivedRecords = \App\Models\SocialContractApproval::whereIn('status', ['Verified', 'Approved', 'Rejected'])
                ->orderBy('updated_at', 'desc')
                ->get()
                ->map(function ($approval) {
                    $dateFormatted = null;
                    if ($approval->date) {
                        try {
                            $dateObj = $approval->date instanceof \Carbon\Carbon ? $approval->date : Carbon::parse($approval->date);
                            $dateFormatted = $dateObj->format('Y-m-d');
                        } catch (\Exception $e) {
                            $dateFormatted = $approval->date;
                        }
                    }
                    
                    // Determine which action date to show
                    $actionDate = null;
                    if ($approval->status === 'Approved' && $approval->approved_at) {
                        $actionDate = $approval->approved_at->format('m-d-Y');
                    } elseif ($approval->status === 'Rejected' && $approval->rejected_at) {
                        $actionDate = $approval->rejected_at->format('m-d-Y');
                    } elseif ($approval->status === 'Verified' && $approval->verified_at) {
                        $actionDate = $approval->verified_at->format('m-d-Y');
                    }
                    
                    return [
                        'id' => $approval->id,
                        'record_id' => $approval->social_contract_record_id,
                        'student_id' => $approval->student_id,
                        'student_name' => $approval->student_name,
                        'event_name' => $approval->event_name,
                        'organization' => $approval->organization,
                        'venue' => $approval->venue,
                        'hours_rendered' => $approval->hours_rendered,
                        'date' => $dateFormatted,
                        'status' => $approval->status, // Keep the actual status (Verified/Approved/Rejected)
                        'verified_at' => $approval->verified_at ? $approval->verified_at->format('m-d-Y') : null,
                        'approved_at' => $approval->approved_at ? $approval->approved_at->format('m-d-Y') : null,
                        'rejected_at' => $approval->rejected_at ? $approval->rejected_at->format('m-d-Y') : null,
                        'action_date' => $actionDate,
                        'rejection_reason' => $approval->rejection_reason,
                        'created_at' => $approval->created_at->toIso8601String(),
                        'updated_at' => $approval->updated_at->toIso8601String(),
                    ];
                });
            
            // Merge pending and archived records
            $allSubmissions = collect()
                ->concat($pendingRecords)
                ->concat($archivedRecords);
            
            \Log::info('Admin getSubmissions - Record counts before deduplication', [
                'pending' => $pendingRecords->count(),
                'archived' => $archivedRecords->count(),
                'total' => $allSubmissions->count(),
            ]);
            
            // Remove duplicates by tracking the underlying social_contract_record_id
            // Approvals take precedence over pending records
            $seen = [];
            $uniqueSubmissions = $allSubmissions->filter(function ($record) use (&$seen) {
                // For approval records, use the social_contract_record_id
                // For pending records, use their own id
                $recordId = isset($record['record_id']) ? $record['record_id'] : $record['id'];
                
                if (in_array($recordId, $seen)) {
                    return false; // Skip duplicate
                }
                
                $seen[] = $recordId;
                return true;
            });
            
            \Log::info('Admin getSubmissions - Final count after deduplication', [
                'unique_submissions' => $uniqueSubmissions->count(),
            ]);

            return response()->json([
                'success' => true,
                'data' => $uniqueSubmissions->values() // Re-index after filtering
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
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
            
            $record = SocialContractRecord::with('socialContract')->findOrFail($id);
            $oldStatus = $record->status;
            
            // Update status
            $record->status = 'Verified';
            $record->save();
            
            // Create notification for student
            StudentNotification::create([
                'user_id' => $record->socialContract->student_id,
                'social_contract_record_id' => $record->id,
                'type' => 'verified',
                'message' => 'Your social contract submission has been verified by the admin.',
                'is_read' => false,
            ]);
            
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
            
            $record = SocialContractRecord::with('socialContract')->findOrFail($id);
            $oldStatus = $record->status;
            
            // Update status and rejection reason
            $record->status = 'Rejected';
            $record->rejection_reason = $validated['reason'];
            
            // Only set rejected_at if the column exists
            if (Schema::hasColumn('social_contract_records', 'rejected_at')) {
                $record->rejected_at = now();
            }
            
            $record->save();
            
            // Create notification for student
            StudentNotification::create([
                'user_id' => $record->socialContract->student_id,
                'social_contract_record_id' => $record->id,
                'type' => 'rejected',
                'message' => 'Your social contract submission has been rejected by the admin.',
                'rejection_reason' => $validated['reason'],
                'is_read' => false,
            ]);
            
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
