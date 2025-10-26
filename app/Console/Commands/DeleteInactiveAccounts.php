<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\StudentNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DeleteInactiveAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:delete-inactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete inactive user accounts after 7 days and send reminder notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $deletionThreshold = $now->copy()->subDays(7);
        
        // Find accounts that have been inactive for 7 days or more
        $accountsToDelete = User::where('status', 'inactive')
            ->whereNotNull('inactive_at')
            ->where('inactive_at', '<=', $deletionThreshold)
            ->get();
        
        if ($accountsToDelete->isEmpty()) {
            $this->info('No accounts to delete.');
        } else {
            foreach ($accountsToDelete as $user) {
                $this->info("Deleting account: {$user->name} (ID: {$user->id}, Student ID: {$user->student_id})");
                
                Log::info('Automatically deleting inactive user account', [
                    'user_id' => $user->id,
                    'student_id' => $user->student_id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'inactive_at' => $user->inactive_at,
                    'days_inactive' => $user->inactive_at->diffInDays($now)
                ]);
                
                $user->delete();
            }
            
            $this->info("Deleted {$accountsToDelete->count()} inactive account(s).");
        }
        
        // Send reminder notifications to accounts that will be deleted soon
        $this->sendReminderNotifications();
        
        return 0;
    }
    
    /**
     * Send reminder notifications to users whose accounts will be deleted soon
     */
    protected function sendReminderNotifications()
    {
        $now = Carbon::now();
        
        // Get inactive accounts
        $inactiveAccounts = User::where('status', 'inactive')
            ->whereNotNull('inactive_at')
            ->get();
        
        foreach ($inactiveAccounts as $user) {
            $daysInactive = $user->inactive_at->diffInDays($now);
            $daysRemaining = 7 - $daysInactive;
            
            // Send reminders on day 1, 3, 5, and 6
            if (in_array($daysInactive, [1, 3, 5, 6])) {
                $deletionDate = $user->inactive_at->copy()->addDays(7)->format('F d, Y');
                
                // Check if notification for this day was already sent
                $existingNotification = StudentNotification::where('user_id', $user->id)
                    ->where('title', 'Account Deletion Reminder')
                    ->whereDate('created_at', $now->toDateString())
                    ->first();
                
                if (!$existingNotification) {
                    StudentNotification::create([
                        'user_id' => $user->id,
                        'title' => 'Account Deletion Reminder',
                        'message' => "Warning: Your account is scheduled for permanent deletion on {$deletionDate}. Only {$daysRemaining} day(s) remaining. Please contact your administrator immediately to reactivate your account.",
                        'type' => 'danger',
                        'is_read' => false
                    ]);
                    
                    $this->info("Sent reminder to: {$user->name} ({$daysRemaining} days remaining)");
                    
                    Log::info('Sent account deletion reminder', [
                        'user_id' => $user->id,
                        'days_remaining' => $daysRemaining,
                        'deletion_date' => $deletionDate
                    ]);
                }
            }
        }
    }
}
