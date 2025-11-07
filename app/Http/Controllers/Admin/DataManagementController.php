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
            $records = SocialContractRecord::with('user')
                ->where('status', 'Rejected')
                ->whereNotNull('rejected_at')
                ->orderBy('rejected_at', 'asc')
                ->get()
                ->map(function ($record) {
                    $rejectedAt = Carbon::parse($record->rejected_at);
                    $daysSince = now()->diffInDays($rejectedAt);
                    
                    return [
                        'id' => $record->id,
                        'student_name' => $record->user->name ?? 'Unknown',
                        'event_name' => $record->event_name,
                        'rejected_at' => $rejectedAt->format('M d, Y'),
                        'days_since_rejection' => $daysSince,
                        'eligible_for_deletion' => $daysSince >= 7,
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
                    $daysInactive = now()->diffInDays($inactiveSince);
                    $deletionDate = $inactiveSince->copy()->addDays(7);
                    $hoursUntilDeletion = max(0, now()->diffInHours($deletionDate, false));
                    $daysUntilDeletion = max(0, (int) ceil($hoursUntilDeletion / 24));
                    
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'student_id' => $user->student_id,
                        'email' => $user->email,
                        'inactive_at' => $inactiveSince->format('M d, Y'),
                        'days_inactive' => $daysInactive,
                        'eligible_for_deletion' => $daysInactive >= 7,
                        'time_until_deletion' => $daysInactive >= 7 
                            ? 'Ready for deletion' 
                            : ($daysUntilDeletion > 0 
                                ? "{$daysUntilDeletion} day(s) remaining" 
                                : "{$hoursUntilDeletion} hour(s) remaining")
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
            
            $daysSince = now()->diffInDays(Carbon::parse($record->rejected_at));
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
            
            $daysInactive = now()->diffInDays(Carbon::parse($user->inactive_at));
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
                ->where('rejected_at', '<', $cutoff)
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
                ->where('inactive_at', '<', $cutoff)
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
