<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SocialContractRecord;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DeleteOldRejectedRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scms:delete-old-rejected';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete social contract records rejected more than 7 days ago';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cutoff = Carbon::now()->subDays(7);
        $this->info('Deleting records rejected before: ' . $cutoff->toDateTimeString());

        $records = SocialContractRecord::where('status', 'Rejected')
            ->whereNotNull('rejected_at')
            ->where('rejected_at', '<', $cutoff)
            ->get();

        $count = $records->count();
        foreach ($records as $rec) {
            try {
                Log::info('Auto-deleting rejected record', ['id' => $rec->id, 'rejected_at' => $rec->rejected_at]);
                $rec->delete();
            } catch (\Exception $e) {
                Log::error('Failed to auto-delete rejected record', ['id' => $rec->id, 'error' => $e->getMessage()]);
            }
        }

        $this->info("Deleted {$count} record(s).");
        return 0;
    }
}
