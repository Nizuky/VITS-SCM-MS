<?php

namespace App\Http\Controllers;

use App\Models\SocialContractRecord;
use App\Models\SocialContractApproval;
use App\Models\SuperAdminActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SuperAdminDashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function getDashboardStats()
    {
        try {
            $weekAgo = Carbon::now()->subDays(7);
            
            // Count verified submissions waiting for approval (all time)
            $pending = SocialContractRecord::where('status', 'Verified')->count();
            
            // Count approved submissions this week
            $approvedThisWeek = SocialContractRecord::where('status', 'Approved')
                ->where('updated_at', '>=', $weekAgo)
                ->count();
            
            // Count rejected submissions this week
            $rejectedThisWeek = SocialContractRecord::where('status', 'Rejected')
                ->where('updated_at', '>=', $weekAgo)
                ->count();
            
            Log::info('SuperAdmin Dashboard Stats', [
                'pending' => $pending,
                'approved_this_week' => $approvedThisWeek,
                'rejected_this_week' => $rejectedThisWeek,
                'week_ago' => $weekAgo->toDateTimeString()
            ]);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'pending' => $pending,
                    'approved_this_week' => $approvedThisWeek,
                    'rejected_this_week' => $rejectedThisWeek
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get superadmin dashboard stats', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load dashboard statistics'
            ], 500);
        }
    }

    /**
     * Get all submissions for the table
     * Pending: from social_contract_records with status 'Pending'
     * For Approval: from social_contract_approvals with status 'Verified'
     * Archived: from social_contract_approvals with status 'Approved' or 'Rejected'
     */
    public function getSubmissions()
    {
        \Log::info('SuperAdminDashboardController@getSubmissions called', [
            'auth_check' => Auth::guard('superadmin')->check(),
            'user_id' => Auth::guard('superadmin')->id(),
            'session_id' => request()->session()->getId(),
        ]);
        
        try {
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
                        'student_id' => $student->student_id ?? $student->id ?? '',
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

            // Get verified records (for approval) from social_contract_approvals
            $forApprovalRecords = \App\Models\SocialContractApproval::where('status', 'Verified')
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
                        'status' => 'Verified',
                        'created_at' => $approval->created_at->toIso8601String(),
                        'updated_at' => $approval->updated_at->toIso8601String(),
                    ];
                });

            // Get archived records (approved/rejected) from social_contract_approvals
            $archivedRecords = \App\Models\SocialContractApproval::whereIn('status', ['Approved', 'Rejected'])
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
                        'status' => $approval->status,
                        'rejection_reason' => $approval->rejection_reason,
                        'created_at' => $approval->created_at->toIso8601String(),
                        'updated_at' => $approval->updated_at->toIso8601String(),
                    ];
                });

            // Merge all records
            $allSubmissions = $pendingRecords
                ->concat($forApprovalRecords)
                ->concat($archivedRecords);
            
            return response()->json([
                'success' => true,
                'data' => $allSubmissions
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get submissions', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load submissions'
            ], 500);
        }
    }

    /**
     * Approve a verified submission (SuperAdmin only)
     * Works with social_contract_approvals table
     */
    public function approveSubmission($id)
    {
        try {
            Log::info('Attempting to approve submission', ['id' => $id]);
            
            // Find the approval record (not the original record)
            $approval = \App\Models\SocialContractApproval::findOrFail($id);
            
            Log::info('Found approval record', [
                'approval_id' => $approval->id,
                'status' => $approval->status,
                'record_id' => $approval->social_contract_record_id
            ]);
            
            // Check if already approved
            if ($approval->status === 'Approved') {
                Log::warning('Approval already approved', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Submission is already approved'
                ], 400);
            }
            
            // Check if verified (SuperAdmin can only approve verified submissions)
            if ($approval->status !== 'Verified') {
                Log::warning('Approval status is not Verified', ['id' => $id, 'status' => $approval->status]);
                return response()->json([
                    'success' => false,
                    'message' => 'Only verified submissions can be approved. Current status: ' . $approval->status
                ], 400);
            }
            
            DB::transaction(function () use ($approval) {
                // Update the approval record status
                $approval->status = 'Approved';
                $approval->approved_by = Auth::guard('superadmin')->id();
                $approval->approved_at = now();
                $approval->save();
                
                // Update the original record status
                if ($approval->socialContractRecord) {
                    $approval->socialContractRecord->status = 'Approved';
                    $approval->socialContractRecord->save();
                }
                
                // Log activity for calendar
                SuperAdminActivityLog::create([
                    'super_admin_id' => Auth::guard('superadmin')->id(),
                    'action' => 'approved_submission',
                    'description' => "Approved submission for {$approval->event_name}",
                    'metadata' => json_encode([
                        'approval_id' => $approval->id,
                        'record_id' => $approval->social_contract_record_id,
                        'event_name' => $approval->event_name,
                        'student_name' => $approval->student_name
                    ])
                ]);
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Submission approved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to approve submission', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve submission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify a pending submission (SuperAdmin can verify directly)
     * Works with social_contract_records table - similar to Admin verify
     * This moves record from "Pending" to "Verified" status and triggers observer
     */
    public function verifySubmission($id)
    {
        try {
            Log::info('SuperAdmin attempting to verify submission', ['id' => $id]);
            
            // Find the record from social_contract_records table
            $record = SocialContractRecord::findOrFail($id);
            
            Log::info('Found record', [
                'record_id' => $record->id,
                'status' => $record->status
            ]);
            
            // Check if already verified
            if ($record->status === 'Verified') {
                return response()->json([
                    'success' => false,
                    'message' => 'Submission is already verified'
                ], 400);
            }
            
            if ($record->status === 'Approved' || $record->status === 'Rejected') {
                return response()->json([
                    'success' => false,
                    'message' => 'Submission has already been processed'
                ], 400);
            }
            
            DB::transaction(function () use ($record) {
                $oldStatus = $record->status;
                
                // Update the record status to Verified
                $record->status = 'Verified';
                $record->save();
                
                // Observer will automatically create approval record
                
                // Log activity for calendar
                SuperAdminActivityLog::create([
                    'super_admin_id' => Auth::guard('superadmin')->id(),
                    'action' => 'verified_submission',
                    'description' => "Verified submission for {$record->event_name}",
                    'metadata' => json_encode([
                        'record_id' => $record->id,
                        'event_name' => $record->event_name,
                        'old_status' => $oldStatus,
                        'new_status' => 'Verified'
                    ])
                ]);
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Submission verified successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('SuperAdmin failed to verify submission', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify submission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a submission
     * Can reject both:
     * 1. Pending records (from social_contract_records table)
     * 2. Verified records (from social_contract_approvals table)
     */
    public function rejectSubmission(Request $request, $id)
    {
        try {
            Log::info('SuperAdmin attempting to reject submission', ['id' => $id]);
            
            $reason = $request->input('reason', 'Rejected by SuperAdmin');
            
            // First, try to find as an approval record (for verified submissions)
            $approval = \App\Models\SocialContractApproval::find($id);
            
            if ($approval) {
                // This is a verified submission (from approvals table)
                Log::info('Found approval record to reject', ['approval_id' => $approval->id]);
                
                // Check if already rejected or approved
                if ($approval->status === 'Rejected') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Submission is already rejected'
                    ], 400);
                }
                
                if ($approval->status === 'Approved') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Approved submissions cannot be rejected'
                    ], 400);
                }
                
                DB::transaction(function () use ($approval, $reason) {
                    // Update the approval record status
                    $approval->status = 'Rejected';
                    $approval->approved_by = Auth::guard('superadmin')->id();
                    $approval->approved_at = now();
                    $approval->rejection_reason = $reason;
                    $approval->save();
                    
                    // Update the original record status
                    if ($approval->socialContractRecord) {
                        $approval->socialContractRecord->status = 'Rejected';
                        $approval->socialContractRecord->save();
                    }
                    
                    // Log activity for calendar
                    SuperAdminActivityLog::create([
                        'super_admin_id' => Auth::guard('superadmin')->id(),
                        'action' => 'rejected_submission',
                        'description' => "Rejected submission for {$approval->event_name}",
                        'metadata' => json_encode([
                            'approval_id' => $approval->id,
                            'record_id' => $approval->social_contract_record_id,
                            'event_name' => $approval->event_name,
                            'student_name' => $approval->student_name,
                            'reason' => $reason
                        ])
                    ]);
                });
                
                return response()->json([
                    'success' => true,
                    'message' => 'Submission rejected successfully'
                ]);
            }
            
            // If not found in approvals, try to find as a pending record
            $record = SocialContractRecord::find($id);
            
            if ($record) {
                // This is a pending submission (from records table)
                Log::info('Found pending record to reject', ['record_id' => $record->id]);
                
                // Check if already rejected
                if ($record->status === 'Rejected') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Submission is already rejected'
                    ], 400);
                }
                
                if ($record->status === 'Approved') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Approved submissions cannot be rejected'
                    ], 400);
                }
                
                DB::transaction(function () use ($record, $reason) {
                    // Update the record status to Rejected
                    $record->status = 'Rejected';
                    $record->rejection_reason = $reason;
                    $record->save();
                    
                    // Log activity for calendar
                    SuperAdminActivityLog::create([
                        'super_admin_id' => Auth::guard('superadmin')->id(),
                        'action' => 'rejected_submission',
                        'description' => "Rejected submission for {$record->event_name}",
                        'metadata' => json_encode([
                            'record_id' => $record->id,
                            'event_name' => $record->event_name,
                            'reason' => $reason
                        ])
                    ]);
                });
                
                return response()->json([
                    'success' => true,
                    'message' => 'Submission rejected successfully'
                ]);
            }
            
            // Not found in either table
            return response()->json([
                'success' => false,
                'message' => 'Submission not found'
            ], 404);
            
        } catch (\Exception $e) {
            Log::error('SuperAdmin failed to reject submission', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject submission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get activity calendar data (last 365 days)
     */
    public function getActivityCalendar()
    {
        try {
            $superAdminId = Auth::guard('superadmin')->id();
            $startDate = Carbon::now()->subYear();
            $endDate = Carbon::now();
            
            // Get all activity logs grouped by date
            $activities = SuperAdminActivityLog::where('super_admin_id', $superAdminId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->get()
                ->pluck('count', 'date')
                ->toArray();
            
            return response()->json([
                'success' => true,
                'data' => $activities
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get activity calendar', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load activity calendar'
            ], 500);
        }
    }
}
