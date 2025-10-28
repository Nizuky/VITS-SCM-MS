<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\SocialContractRecord;
use App\Models\SocialContractApproval;

echo "Updating approved_at timestamps for existing Approved records...\n\n";

// Get all records with status 'Approved'
$approvedRecords = SocialContractRecord::where('status', 'Approved')
    ->with('approval')
    ->get();

$updated = 0;
$skipped = 0;

foreach ($approvedRecords as $record) {
    if ($record->approval && !$record->approval->approved_at) {
        // Use updated_at as the fallback timestamp
        $record->approval->approved_at = $record->approval->updated_at ?? $record->updated_at ?? now();
        $record->approval->status = 'Approved';
        $record->approval->save();
        
        echo "Updated record ID {$record->id} - Set approved_at to {$record->approval->approved_at}\n";
        $updated++;
    } else if ($record->approval && $record->approval->approved_at) {
        echo "Skipped record ID {$record->id} - already has approved_at timestamp\n";
        $skipped++;
    } else {
        echo "Warning: Record ID {$record->id} has no approval record\n";
    }
}

echo "\n✅ Done! Updated {$updated} records, skipped {$skipped} records.\n";
