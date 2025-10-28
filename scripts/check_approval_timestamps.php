<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\SocialContractApproval;

echo "Checking social_contract_approvals table for timestamps:\n\n";

$approvals = SocialContractApproval::select('id', 'status', 'verified_at', 'approved_at', 'rejected_at')
    ->orderBy('id', 'desc')
    ->limit(10)
    ->get();

foreach ($approvals as $approval) {
    echo sprintf(
        "ID: %d | Status: %s | Verified: %s | Approved: %s | Rejected: %s\n",
        $approval->id,
        $approval->status,
        $approval->verified_at ? $approval->verified_at->toISOString() : 'NULL',
        $approval->approved_at ? $approval->approved_at->toISOString() : 'NULL',
        $approval->rejected_at ? $approval->rejected_at->toISOString() : 'NULL'
    );
}
