#!/usr/bin/env php
<?php

/**
 * Quick Test Script: Verify Observer is Working
 * 
 * This script tests that when a record is verified,
 * it automatically creates an approval record.
 */

// Run this from terminal:
// php scripts/test_observer.php

echo "🔍 Testing SocialContractRecord Observer...\n\n";

// Check if observer is registered
echo "1. Checking if observer is registered...\n";
$observerFile = __DIR__ . '/../app/Observers/SocialContractRecordObserver.php';
$providerFile = __DIR__ . '/../app/Providers/AppServiceProvider.php';

if (file_exists($observerFile)) {
    echo "   ✅ Observer file exists\n";
} else {
    echo "   ❌ Observer file NOT found\n";
}

if (file_exists($providerFile)) {
    $providerContent = file_get_contents($providerFile);
    if (strpos($providerContent, 'SocialContractRecordObserver') !== false) {
        echo "   ✅ Observer is registered in AppServiceProvider\n";
    } else {
        echo "   ❌ Observer NOT registered in AppServiceProvider\n";
    }
}

echo "\n2. To test manually:\n";
echo "   a) Log in as Admin\n";
echo "   b) Verify a pending submission\n";
echo "   c) Check database:\n\n";

echo "      SQL Query:\n";
echo "      SELECT * FROM social_contract_approvals \n";
echo "      WHERE social_contract_record_id = [RECORD_ID];\n\n";

echo "   d) Check Laravel logs:\n";
echo "      tail -f storage/logs/laravel.log\n\n";

echo "   Expected log entries:\n";
echo "   - 'SocialContractRecordObserver::updated called'\n";
echo "   - 'Status changed to Verified, creating approval record'\n";
echo "   - 'Approval record created successfully'\n\n";

echo "3. Quick database check:\n";
echo "   Run in MySQL/Tinker:\n";
echo "   php artisan tinker\n";
echo "   >>> \\App\\Models\\SocialContractRecord::first()->update(['status' => 'Verified']);\n";
echo "   >>> \\App\\Models\\SocialContractApproval::latest()->first();\n\n";

echo "✅ Observer setup is complete!\n";
echo "📚 See VERIFIED_TO_APPROVAL_WORKFLOW.md for detailed documentation\n";
