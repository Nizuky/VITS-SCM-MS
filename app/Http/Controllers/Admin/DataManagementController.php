<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialContractRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DataManagementController extends Controller
{
    /**
     * Get rejected records with deletion eligibility info
     */
    public function getRejectedRecords()
    {
        try {
            // Fetch rejected records; SocialContractRecord does not have a direct user relation
            // so we eager load the socialContract and its student for proper student name resolution.
            $records = SocialContractRecord::with(['socialContract.student'])
                ->where('status', 'Rejected')
                ->whereNotNull('rejected_at')
                ->orderBy('rejected_at', 'asc')
                ->get()
                ->map(function ($record) {
                    $rejectedAt = Carbon::parse($record->rejected_at);
                    // Whole-number, non-negative days since rejection
                    $daysSince = (int) abs(now()->diffInDays($rejectedAt, false));
                    $daysRemaining = max(0, 7 - $daysSince);
                    
                    return [
                        'id' => $record->id,
                        'student_name' => optional($record->socialContract->student)->name ?? 'Unknown',
                        'event_name' => $record->event_name,
                        'rejected_at' => $rejectedAt->format('M d, Y'),
                        'days_since_rejection' => $daysSince,
                        'eligible_for_deletion' => $daysSince >= 7,
                        'days_remaining' => $daysRemaining,
                        'deletion_status' => $daysSince >= 7 ? 'Ready' : "In {$daysRemaining} day(s)",
                    ];
                });

            return response()->json([
                'success' => true,
                'records' => $records
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching rejected records', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to load rejected records'
            ], 500);
        }
    }

    /**
     * Get inactive accounts with deletion eligibility info
     */
    public function getInactiveAccounts()
    {
        try {
            $accounts = User::where('status', 'inactive')
                ->whereNotNull('inactive_at')
                ->orderBy('inactive_at', 'asc')
                ->get()
                ->map(function ($user) {
                    $inactiveSince = Carbon::parse($user->inactive_at);
                    // Ensure whole number days, never negative.
                    $daysInactive = (int) abs(now()->diffInDays($inactiveSince, false));
                    $deletionDate = $inactiveSince->copy()->addDays(7);
                    $daysRemaining = max(0, 7 - $daysInactive);
                    
                    // Calculate hours until deletion only if not yet eligible
                    $timeUntilDeletion = 'Ready';
                    if ($daysInactive < 7) {
                        $hoursRemaining = max(0, (int) abs(now()->diffInHours($deletionDate, false)));
                        if ($daysRemaining > 0) {
                            $timeUntilDeletion = "In {$daysRemaining} day(s)";
                        } else {
                            $timeUntilDeletion = "In {$hoursRemaining} hour(s)";
                        }
                    }
                    
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'student_id' => $user->student_id,
                        'email' => $user->email,
                        'inactive_at' => $inactiveSince->format('M d, Y'),
                        'days_inactive' => $daysInactive,
                        'eligible_for_deletion' => $daysInactive >= 7,
                        'time_until_deletion' => $timeUntilDeletion,
                    ];
                });

            return response()->json([
                'success' => true,
                'accounts' => $accounts
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching inactive accounts', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to load inactive accounts'
            ], 500);
        }
    }

    /**
     * Delete a single rejected record
     */
    public function deleteRecord($id)
    {
        try {
            $record = SocialContractRecord::findOrFail($id);
            
            // Verify it's rejected and older than 7 days
            if ($record->status !== 'Rejected' || !$record->rejected_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'Record is not eligible for deletion'
                ], 400);
            }
            
            $rejectedAt = Carbon::parse($record->rejected_at);
            $daysSince = $rejectedAt->diffInDays(now(), false);
            
            // Log for debugging
            Log::info('Checking record deletion eligibility', [
                'record_id' => $record->id,
                'rejected_at' => $rejectedAt->toDateTimeString(),
                'now' => now()->toDateTimeString(),
                'days_since' => $daysSince,
                'eligible' => $daysSince >= 7
            ]);
            
            if ($daysSince < 7) {
                return response()->json([
                    'success' => false,
                    'message' => 'Record must be rejected for at least 7 days before deletion'
                ], 400);
            }
            
            Log::info('Admin manually deleted rejected record', [
                'record_id' => $record->id,
                'admin_id' => auth()->guard('admin')->id(),
                'rejected_at' => $record->rejected_at,
                'days_since_rejection' => $daysSince
            ]);
            
            $record->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Record deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting record', ['error' => $e->getMessage(), 'id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete record'
            ], 500);
        }
    }

    /**
     * Delete a single inactive account
     */
    public function deleteAccount($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Verify it's inactive and older than 7 days
            if ($user->status !== 'inactive' || !$user->inactive_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'Account is not eligible for deletion'
                ], 400);
            }
            
            $inactiveAt = Carbon::parse($user->inactive_at);
            $daysInactive = $inactiveAt->diffInDays(now(), false);
            
            Log::info('Checking account deletion eligibility', [
                'user_id' => $user->id,
                'inactive_at' => $inactiveAt->toDateTimeString(),
                'now' => now()->toDateTimeString(),
                'days_inactive' => $daysInactive,
                'eligible' => $daysInactive >= 7
            ]);
            
            if ($daysInactive < 7) {
                return response()->json([
                    'success' => false,
                    'message' => 'Account must be inactive for at least 7 days before deletion'
                ], 400);
            }
            
            Log::info('Admin manually deleted inactive account', [
                'user_id' => $user->id,
                'admin_id' => auth()->guard('admin')->id(),
                'inactive_at' => $user->inactive_at,
                'days_inactive' => $daysInactive
            ]);
            
            $user->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Account deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting account', ['error' => $e->getMessage(), 'id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete account'
            ], 500);
        }
    }

    /**
     * Delete all eligible rejected records (7+ days old)
     */
    public function deleteAllEligibleRecords()
    {
        try {
            $cutoff = Carbon::now()->subDays(7);
            
            $records = SocialContractRecord::where('status', 'Rejected')
                ->whereNotNull('rejected_at')
                ->where('rejected_at', '<=', $cutoff)
                ->get();
            
            $count = $records->count();
            
            Log::info('Admin bulk deleting rejected records', [
                'admin_id' => auth()->guard('admin')->id(),
                'count' => $count,
                'cutoff_date' => $cutoff->toDateTimeString()
            ]);
            
            foreach ($records as $record) {
                $record->delete();
            }
            
            return response()->json([
                'success' => true,
                'count' => $count,
                'message' => "{$count} record(s) deleted successfully"
            ]);
        } catch (\Exception $e) {
            Log::error('Error bulk deleting records', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete records'
            ], 500);
        }
    }

    /**
     * Delete all eligible inactive accounts (7+ days inactive)
     */
    public function deleteAllEligibleAccounts()
    {
        try {
            $cutoff = Carbon::now()->subDays(7);
            
            $users = User::where('status', 'inactive')
                ->whereNotNull('inactive_at')
                ->where('inactive_at', '<=', $cutoff)
                ->get();
            
            $count = $users->count();
            
            Log::info('Admin bulk deleting inactive accounts', [
                'admin_id' => auth()->guard('admin')->id(),
                'count' => $count,
                'cutoff_date' => $cutoff->toDateTimeString()
            ]);
            
            foreach ($users as $user) {
                $user->delete();
            }
            
            return response()->json([
                'success' => true,
                'count' => $count,
                'message' => "{$count} account(s) deleted successfully"
            ]);
        } catch (\Exception $e) {
            Log::error('Error bulk deleting accounts', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete accounts'
            ], 500);
        }
    }
}
