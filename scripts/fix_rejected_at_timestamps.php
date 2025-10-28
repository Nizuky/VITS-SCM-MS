<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\SocialContractRecord;
use App\Models\SocialContractApproval;

echo "Fixing rejected_at timestamps for Rejected records...\n\n";

// Get all records with status 'Rejected'
$rejectedRecords = SocialContractRecord::where('status', 'Rejected')
    ->with('approval')
    ->get();

$updated = 0;
$created = 0;
$skipped = 0;

foreach ($rejectedRecords as $record) {
    echo "Record ID {$record->id} ({$record->event_name}): ";
    
    // Use updated_at or now as fallback for rejection time
    $rejectionTime = $record->updated_at ?? now();
    
    if ($record->approval) {
        if (!$record->approval->rejected_at) {
            $record->approval->rejected_at = $rejectionTime;
            $record->approval->status = 'Rejected';
            $record->approval->save();
            echo "✅ Updated approval record with rejected_at: {$rejectionTime}\n";
            $updated++;
        } else {
            echo "✓ Already has rejected_at: {$record->approval->rejected_at}\n";
            $skipped++;
        }
    } else {
        // No approval record exists, create one
        SocialContractApproval::create([
            'social_contract_record_id' => $record->id,
            'student_id' => $record->socialContract->student->student_id ?? '',
            'student_name' => $record->socialContract->student->name ?? '',
            'event_name' => $record->event_name,
            'organization' => $record->organization,
            'venue' => $record->venue,
            'hours_rendered' => $record->hours_rendered,
            'date' => $record->date,
            'status' => 'Rejected',
            'rejected_at' => $rejectionTime,
            'rejection_reason' => $record->rejection_reason,
        ]);
        echo "✅ Created approval record with rejected_at: {$rejectionTime}\n";
        $created++;
    }
}

echo "\n✅ Done! Updated {$updated} records, created {$created} approval records, skipped {$skipped} records.\n";
