<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\SuperAdminActivityLog;
use App\Models\SocialContractRecord;
use App\Models\SocialContractApproval;

echo "Checking approval timestamps from activity logs...\n\n";

// Get recent approval activities
$approvalActivities = SuperAdminActivityLog::where('action', 'approved_submission')
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get();

foreach ($approvalActivities as $activity) {
    $metadata = json_decode($activity->metadata, true);
    $recordId = $metadata['record_id'] ?? null;
    
    echo sprintf(
        "Activity: %s | Record ID: %s | Actual Approval Time: %s\n",
        $activity->description,
        $recordId,
        $activity->created_at->toISOString()
    );
    
    if ($recordId) {
        $record = SocialContractRecord::find($recordId);
        if ($record && $record->approval) {
            echo sprintf(
                "  Current approval->approved_at: %s\n",
                $record->approval->approved_at ? $record->approval->approved_at->toISOString() : 'NULL'
            );
            
            // Update if different
            if (!$record->approval->approved_at || $record->approval->approved_at->toISOString() !== $activity->created_at->toISOString()) {
                $record->approval->approved_at = $activity->created_at;
                $record->approval->save();
                echo "  ✅ Updated to match activity log timestamp\n";
            } else {
                echo "  ✓ Already correct\n";
            }
        }
    }
    echo "\n";
}

echo "Done!\n";
