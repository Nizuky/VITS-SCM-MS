<?php

namespace App\Http\Controllers;

use App\Models\SocialContractRecord;
use App\Models\SuperAdminActivityLog;
use App\Models\StudentNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class SuperAdminDashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(Request $request)
    {
        try {
            // Ensure session marker is present
            if (!$request->session()->has('superadmin_session_active')) {
                $request->session()->put('superadmin_session_active', true);
                $request->session()->save();
                Log::info('Restored missing superadmin session marker in getDashboardStats');
            }
            
            $now = Carbon::now();
            $startOfMonth = $now->copy()->startOfMonth(); // First day of current month
            $endOfMonth = $now->copy()->endOfMonth(); // Last day of current month
            
            // Count verified submissions waiting for approval (all time)
            $pending = SocialContractRecord::where('status', 'Verified')->count();
            
            // Count approved submissions this month
            $approvedThisMonth = SocialContractRecord::where('status', 'Approved')
                ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
                ->count();
            
            // Count rejected submissions this month
            $rejectedThisMonth = SocialContractRecord::where('status', 'Rejected')
                ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
                ->count();
            
            Log::info('SuperAdmin Dashboard Stats', [
                'pending' => $pending,
                'approved_this_month' => $approvedThisMonth,
                'rejected_this_month' => $rejectedThisMonth,
                'start_of_month' => $startOfMonth->toDateTimeString(),
                'end_of_month' => $endOfMonth->toDateTimeString(),
                'now' => $now->toDateTimeString()
            ]);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'pending' => $pending,
                    'approved_this_week' => $approvedThisMonth, // Keep same key for frontend compatibility
                    'rejected_this_week' => $rejectedThisMonth  // Keep same key for frontend compatibility
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
    public function getSubmissions(Request $request)
    {
        // Ensure session marker is present
        if (!$request->session()->has('superadmin_session_active')) {
            $request->session()->put('superadmin_session_active', true);
            $request->session()->save();
            Log::info('Restored missing superadmin session marker in getSubmissions');
        }
        
        \Log::info('SuperAdminDashboardController@getSubmissions called', [
            'auth_check' => Auth::guard('superadmin')->check(),
            'user_id' => Auth::guard('superadmin')->id(),
            'session_id' => request()->session()->getId(),
        ]);
        
        try {
            $allSubmissions = collect();
            
            // Log table counts for debugging
            $pendingCount = SocialContractRecord::where('status', 'Pending')->count();
            $verifiedInRecordsCount = SocialContractRecord::where('status', 'Verified')->count();
            $verifiedInApprovalsCount = \App\Models\SocialContractApproval::where('status', 'Verified')->count();
            $approvedCount = \App\Models\SocialContractApproval::where('status', 'Approved')->count();
            $rejectedCount = \App\Models\SocialContractApproval::where('status', 'Rejected')->count();
            
            \Log::info('SuperAdmin getSubmissions - Table counts', [
                'pending_in_records' => $pendingCount,
                'verified_in_records' => $verifiedInRecordsCount,
                'verified_in_approvals' => $verifiedInApprovalsCount,
                'approved_in_approvals' => $approvedCount,
                'rejected_in_approvals' => $rejectedCount,
            ]);
            
            // PENDING TAB DATA SOURCE:
            // Get ALL pending records directly from social_contract_records table
            // This shows all student submissions that haven't been verified or rejected yet
            // Both Admin and Super Admin see the SAME pending records from this table
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
                        'supervisor_name' => $record->supervisor_name,
                        'venue' => $record->venue,
                        'hours_rendered' => $record->hours_rendered,
                        'date' => $dateFormatted,
                        'status' => 'Pending',
                        'created_at' => $record->created_at->toIso8601String(),
                        'updated_at' => $record->updated_at->toIso8601String(),
                    ];
                });

            // FOR APPROVAL TAB DATA SOURCE (Super Admin Only):
            // Get verified records directly from social_contract_records table
            // These are records that Admin/Super Admin verified and are awaiting final approval
            // Only Super Admin can approve/reject these records
            $forApprovalRecords = SocialContractRecord::where('status', 'Verified')
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
                    
                    // Set action_date for verified records
                    $actionDate = null;
                    if ($record->updated_at) {
                        $actionDate = $record->updated_at->format('m-d-Y');
                    }
                    
                    return [
                        'id' => $record->id,
                        'student_id' => $student ? ($student->student_id ?? 'N/A') : 'N/A',
                        'student_name' => $student->name ?? '',
                        'event_name' => $record->event_name,
                        'organization' => $record->organization,
                        'supervisor_name' => $record->supervisor_name,
                        'venue' => $record->venue,
                        'hours_rendered' => $record->hours_rendered,
                        'date' => $dateFormatted,
                        'status' => 'Verified',
                        'action_date' => $actionDate,
                        'rejection_reason' => $record->rejection_reason ?? '',
                        'created_at' => $record->created_at->toIso8601String(),
                        'updated_at' => $record->updated_at->toIso8601String(),
                    ];
                });

            // ARCHIVED TAB DATA SOURCE:
            // Get ALL archived records directly from social_contract_records table
            // This includes: Verified, Approved, and Rejected records
            // Both Admin and Super Admin see the SAME archived data from this table
            $archivedRecords = SocialContractRecord::whereIn('status', ['Verified', 'Approved', 'Rejected'])
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
                    
                    // Determine which action date to show based on status
                    $actionDate = null;
                    if ($record->updated_at) {
                        $actionDate = $record->updated_at->format('m-d-Y');
                    }
                    
                    return [
                        'id' => $record->id,
                        'student_id' => $student ? ($student->student_id ?? 'N/A') : 'N/A',
                        'student_name' => $student->name ?? '',
                        'event_name' => $record->event_name,
                        'organization' => $record->organization,
                        'supervisor_name' => $record->supervisor_name,
                        'venue' => $record->venue,
                        'hours_rendered' => $record->hours_rendered,
                        'date' => $dateFormatted,
                        'status' => $record->status,
                        'rejection_reason' => $record->rejection_reason ?? '',
                        'action_date' => $actionDate,
                        'created_at' => $record->created_at->toIso8601String(),
                        'updated_at' => $record->updated_at->toIso8601String(),
                    ];
                });

            // Merge all records
            $allSubmissions = collect()
                ->concat($pendingRecords)
                ->concat($forApprovalRecords)
                ->concat($archivedRecords);
            
            \Log::info('SuperAdmin getSubmissions - Record counts before deduplication', [
                'pending' => $pendingRecords->count(),
                'for_approval' => $forApprovalRecords->count(),
                'archived' => $archivedRecords->count(),
                'total' => $allSubmissions->count(),
            ]);
            
            // Remove duplicates by tracking record IDs
            // Since all data comes from social_contract_records, we just use id
            $seen = [];
            $uniqueSubmissions = $allSubmissions->filter(function ($record) use (&$seen) {
                $recordId = $record['id'];
                
                if (in_array($recordId, $seen)) {
                    return false; // Skip duplicate
                }
                
                $seen[] = $recordId;
                return true;
            });
            
            \Log::info('SuperAdmin getSubmissions - Final count after deduplication', [
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
    public function approveSubmission(Request $request, $id)
    {
        try {
            // Ensure session marker is present
            if (!$request->session()->has('superadmin_session_active')) {
                $request->session()->put('superadmin_session_active', true);
                $request->session()->save();
                Log::info('Restored missing superadmin session marker in approveSubmission');
            }
            
            Log::info('Attempting to approve submission', ['id' => $id]);
            
            // Find the record directly from social_contract_records
            $record = SocialContractRecord::with('socialContract.student')->findOrFail($id);
            
            Log::info('Found record', [
                'record_id' => $record->id,
                'status' => $record->status
            ]);
            
            // Check if already approved
            if ($record->status === 'Approved') {
                Log::warning('Record already approved', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Submission is already approved'
                ], 400);
            }
            
            // Check if verified (SuperAdmin can only approve verified submissions)
            if ($record->status !== 'Verified') {
                Log::warning('Record status is not Verified', ['id' => $id, 'status' => $record->status]);
                return response()->json([
                    'success' => false,
                    'message' => 'Only verified submissions can be approved. Current status: ' . $record->status
                ], 400);
            }
            
            DB::transaction(function () use ($record) {
                // Update the record status to Approved
                $record->status = 'Approved';
                $record->approved_by = Auth::guard('superadmin')->id();
                $record->approved_at = now();
                $record->save();
                
                // Create notification for student
                StudentNotification::create([
                    'user_id' => $record->socialContract->student_id,
                    'social_contract_record_id' => $record->id,
                    'type' => 'approved',
                    'message' => 'Your social contract submission has been approved by the super admin.',
                    'is_read' => false,
                ]);
                
                // Log activity for calendar
                SuperAdminActivityLog::create([
                    'super_admin_id' => Auth::guard('superadmin')->id(),
                    'action' => 'approved_submission',
                    'description' => "Approved submission for {$record->socialContract->event_name}",
                    'metadata' => json_encode([
                        'record_id' => $record->id,
                        'event_name' => $record->socialContract->event_name ?? 'N/A',
                        'student_name' => $record->socialContract->student->name ?? 'N/A'
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
    public function verifySubmission(Request $request, $id)
    {
        try {
            // Ensure session marker is present
            if (!$request->session()->has('superadmin_session_active')) {
                $request->session()->put('superadmin_session_active', true);
                $request->session()->save();
                Log::info('Restored missing superadmin session marker in verifySubmission');
            }
            
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
     * Works with social_contract_records table directly
     */
    public function rejectSubmission(Request $request, $id)
    {
        try {
            // Ensure session marker is present
            if (!$request->session()->has('superadmin_session_active')) {
                $request->session()->put('superadmin_session_active', true);
                $request->session()->save();
                Log::info('Restored missing superadmin session marker in rejectSubmission');
            }
            
            Log::info('SuperAdmin attempting to reject submission', ['id' => $id]);
            
            $reason = $request->input('reason', 'Rejected by SuperAdmin');
            
            // Find the record directly from social_contract_records
            $record = SocialContractRecord::with('socialContract.student')->findOrFail($id);
            
            Log::info('Found record to reject', ['record_id' => $record->id, 'status' => $record->status]);
            
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
                $record->rejected_by = Auth::guard('superadmin')->id();
                
                // Set rejected_at if the column exists
                if (Schema::hasColumn('social_contract_records', 'rejected_at')) {
                    $record->rejected_at = now();
                }
                
                $record->save();
                
                // Create notification for student
                StudentNotification::create([
                    'user_id' => $record->socialContract->student_id,
                    'social_contract_record_id' => $record->id,
                    'type' => 'rejected',
                    'message' => 'Your social contract submission has been rejected by the super admin.',
                    'rejection_reason' => $reason,
                    'is_read' => false,
                ]);
                
                // Log activity for calendar
                SuperAdminActivityLog::create([
                    'super_admin_id' => Auth::guard('superadmin')->id(),
                    'action' => 'rejected_submission',
                    'description' => "Rejected submission for {$record->socialContract->event_name}",
                    'metadata' => json_encode([
                        'record_id' => $record->id,
                        'event_name' => $record->socialContract->event_name ?? 'N/A',
                        'student_name' => $record->socialContract->student->name ?? 'N/A',
                        'reason' => $reason
                    ])
                ]);
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Submission rejected successfully'
            ]);
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
     * Get a specific submission details for editing
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
     * Get activity calendar data for a specific year
     */
    public function getActivityCalendar(Request $request)
    {
        try {
            $superAdminId = Auth::guard('superadmin')->id();
            $year = $request->input('year', Carbon::now()->year);
            
            // Validate year
            $year = (int)$year;
            $currentYear = Carbon::now()->year;
            if ($year > $currentYear) {
                $year = $currentYear;
            }
            
            $startDate = Carbon::create($year, 1, 1, 0, 0, 0, config('app.timezone'))->startOfDay();
            $endDate = Carbon::create($year, 12, 31, 23, 59, 59, config('app.timezone'))->endOfDay();
            
            Log::info('Admin activity calendar requested', [
                'year' => $year,
                'timezone' => config('app.timezone')
            ]);
            
            // Get all activity logs grouped by date in the application's timezone
            $activities = SuperAdminActivityLog::where('super_admin_id', $superAdminId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get()
                ->groupBy(function($item) {
                    // Group by date in the application's timezone
                    return Carbon::parse($item->created_at)->timezone(config('app.timezone'))->format('Y-m-d');
                })
                ->map(function($group) {
                    return $group->count();
                })
                ->toArray();
            
            Log::info('Admin activity calendar data', [
                'count' => count($activities),
                'year' => $year
            ]);
            
            return response()->json([
                'success' => true,
                'data' => $activities,
                'year' => $year
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get activity calendar', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load activity calendar'
            ], 500);
        }
    }
    
    /**
     * Get activity details for a specific date
     */
    public function getActivityDetails(Request $request)
    {
        try {
            $superAdminId = Auth::guard('superadmin')->id();
            $superAdminName = Auth::guard('superadmin')->user()->name;
            $date = $request->input('date');
            
            if (!$date) {
                return response()->json([
                    'success' => false,
                    'message' => 'Date parameter is required'
                ], 400);
            }
            
            // Parse date in the application's timezone to avoid timezone conversion issues
            $startOfDay = Carbon::parse($date, config('app.timezone'))->startOfDay();
            $endOfDay = Carbon::parse($date, config('app.timezone'))->endOfDay();
            
            Log::info('Activity details date range', [
                'date' => $date,
                'startOfDay' => $startOfDay->toDateTimeString(),
                'endOfDay' => $endOfDay->toDateTimeString(),
                'timezone' => config('app.timezone')
            ]);
            
            // Get all activities for this date with related record information
            $activities = SuperAdminActivityLog::where('super_admin_id', $superAdminId)
                ->whereBetween('created_at', [$startOfDay, $endOfDay])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($log) use ($superAdminName) {
                    $data = [
                        'id' => $log->id,
                        'action' => $log->action,
                        'description' => $log->description,
                        'created_at' => $log->created_at->toIso8601String(),
                        'student_id' => null,
                        'student_name' => null,
                        'venue' => null,
                        'admin_name' => $superAdminName
                    ];
                    
                    // Extract record_id from metadata JSON
                    $recordId = null;
                    if ($log->metadata) {
                        $metadata = json_decode($log->metadata, true);
                        $recordId = $metadata['record_id'] ?? null;
                    }
                    
                    // Try to get related record information
                    if ($recordId) {
                        $record = \App\Models\SocialContractRecord::with('socialContract.student')->find($recordId);
                        if ($record) {
                            \Log::info('Found record', [
                                'record_id' => $record->id,
                                'has_social_contract' => $record->socialContract !== null,
                                'has_student' => $record->socialContract && $record->socialContract->student !== null
                            ]);
                            
                            if ($record->socialContract && $record->socialContract->student) {
                                $data['student_id'] = $record->socialContract->student->student_id ?? null;
                                $data['student_name'] = $record->socialContract->student->name ?? null;
                            }
                            $data['venue'] = $record->venue ?? null;
                        } else {
                            \Log::warning('Record not found', ['record_id' => $recordId]);
                        }
                    }
                    
                    \Log::info('Activity data', $data);
                    
                    return $data;
                });
            
            \Log::info('Returning activities', ['count' => $activities->count()]);
            
            return response()->json([
                'success' => true,
                'data' => $activities,
                'date' => $date
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get activity details', [
                'error' => $e->getMessage(),
                'date' => $request->input('date')
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load activity details'
            ], 500);
        }
    }

    /**
     * Get all support tickets (for super admin)
     */
    public function getSupportTickets(Request $request)
    {
        try {
            // Ensure session marker is present
            if (!$request->session()->has('superadmin_session_active')) {
                $request->session()->put('superadmin_session_active', true);
                $request->session()->save();
            }

            $tickets = \App\Models\SupportTicket::with('student')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($ticket) {
                    return [
                        'id' => $ticket->id,
                        'student_id' => $ticket->student_id,
                        'student_name' => $ticket->student_name,
                        'type' => $ticket->issue_type,
                        'details' => $ticket->details,
                        'status' => $ticket->status,
                        'date' => $ticket->created_at->format('Y-m-d'),
                        'submitted_at' => $ticket->created_at->format('M d, Y g:i A'),
                        'updated_at' => $ticket->updated_at->format('M d, Y g:i A'),
                    ];
                });

            return response()->json([
                'success' => true,
                'tickets' => $tickets
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get support tickets', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load support tickets'
            ], 500);
        }
    }

    /**
     * Update support ticket status (for super admin)
     */
    public function updateTicketStatus(Request $request, $id)
    {
        try {
            // Ensure session marker is present
            if (!$request->session()->has('superadmin_session_active')) {
                $request->session()->put('superadmin_session_active', true);
                $request->session()->save();
            }

            $ticket = \App\Models\SupportTicket::findOrFail($id);
            
            $validated = $request->validate([
                'status' => 'required|in:Pending,Resolved,Closed'
            ]);

            $ticket->status = $validated['status'];
            $ticket->save();

            return response()->json([
                'success' => true,
                'message' => 'Ticket status updated successfully',
                'ticket' => [
                    'id' => $ticket->id,
                    'student_id' => $ticket->student_id,
                    'student_name' => $ticket->student_name,
                    'type' => $ticket->issue_type,
                    'details' => $ticket->details,
                    'status' => $ticket->status,
                    'date' => $ticket->created_at->format('Y-m-d'),
                    'submitted_at' => $ticket->created_at->format('M d, Y g:i A'),
                    'updated_at' => $ticket->updated_at->format('M d, Y g:i A'),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update ticket status', [
                'error' => $e->getMessage(),
                'ticket_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update ticket status'
            ], 500);
        }
    }

    /**
     * Resolve support ticket and notify student
     */
    public function resolveTicket(Request $request, $id)
    {
        try {
            // Ensure session marker is present
            if (!$request->session()->has('superadmin_session_active')) {
                $request->session()->put('superadmin_session_active', true);
                $request->session()->save();
            }

            $ticket = \App\Models\SupportTicket::findOrFail($id);
            
            // Only allow resolving pending tickets
            if ($ticket->status !== 'Pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending tickets can be resolved'
                ], 400);
            }

            $ticket->status = 'Resolved';
            $ticket->save();

            // Create notification for the student
            StudentNotification::create([
                'user_id' => $ticket->student_id,
                'type' => 'ticket_resolved',
                'title' => 'Support Ticket Resolved',
                'message' => "Your support ticket (#{$ticket->id}) regarding '{$ticket->issue_type}' has been resolved by the admin. Please review and mark as done if satisfied.",
                'is_read' => false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ticket resolved successfully. Student has been notified.',
                'ticket' => [
                    'id' => $ticket->id,
                    'student_id' => $ticket->student_id,
                    'student_name' => $ticket->student_name,
                    'type' => $ticket->issue_type,
                    'details' => $ticket->details,
                    'status' => $ticket->status,
                    'date' => $ticket->created_at->format('Y-m-d'),
                    'submitted_at' => $ticket->created_at->format('M d, Y g:i A'),
                    'updated_at' => $ticket->updated_at->format('M d, Y g:i A'),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to resolve ticket', [
                'error' => $e->getMessage(),
                'ticket_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to resolve ticket'
            ], 500);
        }
    }
}
