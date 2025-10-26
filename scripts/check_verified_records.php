<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\SocialContractRecord;
use App\Models\SocialContractApproval;

echo "=== Checking Verified Records Mismatch ===\n\n";

// Get verified records from social_contract_records
$verifiedRecords = SocialContractRecord::where('status', 'Verified')
    ->orderBy('id')
    ->get();

echo "Verified records in social_contract_records table: " . $verifiedRecords->count() . "\n";
foreach ($verifiedRecords as $record) {
    echo "  - Record ID: {$record->id}, Event: {$record->event_name}\n";
}

echo "\n";

// Get verified approvals
$verifiedApprovals = SocialContractApproval::where('status', 'Verified')
    ->orderBy('social_contract_record_id')
    ->get();

echo "Verified records in social_contract_approvals table: " . $verifiedApprovals->count() . "\n";
foreach ($verifiedApprovals as $approval) {
    echo "  - Approval ID: {$approval->id}, Record ID: {$approval->social_contract_record_id}, Event: {$approval->event_name}\n";
}

echo "\n";

// Find missing records
$recordIds = $verifiedRecords->pluck('id')->toArray();
$approvalRecordIds = $verifiedApprovals->pluck('social_contract_record_id')->toArray();

$missing = array_diff($recordIds, $approvalRecordIds);

if (!empty($missing)) {
    echo "=== MISSING RECORDS IN APPROVALS TABLE ===\n";
    foreach ($missing as $recordId) {
        $record = $verifiedRecords->firstWhere('id', $recordId);
        echo "  - Record ID: {$recordId}, Event: {$record->event_name}, Date: {$record->date}\n";
        
        // Try to find the social contract and student
        $socialContract = $record->socialContract()->with('student')->first();
        if ($socialContract && $socialContract->student) {
            $student = $socialContract->student;
            echo "    Student ID: {$student->student_id}, Name: {$student->name}\n";
        } else {
            echo "    ERROR: No student found for this record!\n";
        }
    }
    
    echo "\n=== FIXING MISSING RECORDS ===\n";
    foreach ($missing as $recordId) {
        $record = SocialContractRecord::with('socialContract.student')->find($recordId);
        if ($record && $record->socialContract && $record->socialContract->student) {
            $student = $record->socialContract->student;
            
            // Create the missing approval record
            $approval = SocialContractApproval::create([
                'social_contract_record_id' => $record->id,
                'student_id' => $student->student_id ?? 'N/A',
                'student_name' => $student->name,
                'event_name' => $record->event_name,
                'organization' => $record->organization,
                'venue' => $record->venue,
                'hours_rendered' => $record->hours_rendered,
                'date' => $record->date,
                'status' => 'Verified',
                'verified_by' => null,
                'verified_at' => $record->updated_at,
            ]);
            
            echo "  ✓ Created approval record ID: {$approval->id} for record ID: {$recordId}\n";
        } else {
            echo "  ✗ Could not fix record ID: {$recordId} - missing student data\n";
        }
    }
} else {
    echo "✓ All verified records are properly synced!\n";
}

echo "\n=== Final Count ===\n";
echo "Records: " . SocialContractRecord::where('status', 'Verified')->count() . "\n";
echo "Approvals: " . SocialContractApproval::where('status', 'Verified')->count() . "\n";
