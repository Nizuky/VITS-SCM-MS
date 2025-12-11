<?php

/**
 * Test script to verify that student name/ID changes propagate across the system
 * Run: php scripts/test_student_update_propagation.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\SocialContractApproval;
use App\Models\SupportTicket;
use Illuminate\Support\Facades\DB;

echo "\n=== Testing Student Information Update Propagation ===\n\n";

// Find a student with existing data
$student = User::whereHas('socialContracts')->first();

if (!$student) {
    echo "❌ No students with social contracts found. Please create test data first.\n\n";
    exit(1);
}

echo "Testing with Student:\n";
echo "  ID: {$student->id}\n";
echo "  Student ID: {$student->student_id}\n";
echo "  Name: {$student->name}\n";
echo "  Email: {$student->email}\n\n";

// Check existing records BEFORE update
echo "📊 Checking existing records BEFORE update:\n\n";

$approvalsBefore = SocialContractApproval::where('student_id', $student->student_id)->get();
$ticketsBefore = SupportTicket::where('student_id', $student->id)->get();

echo "  Social Contract Approvals: {$approvalsBefore->count()} records\n";
if ($approvalsBefore->count() > 0) {
    echo "    Sample: {$approvalsBefore->first()->student_name} ({$approvalsBefore->first()->student_id})\n";
}

echo "  Support Tickets: {$ticketsBefore->count()} records\n";
if ($ticketsBefore->count() > 0) {
    echo "    Sample: {$ticketsBefore->first()->student_name}\n";
}

echo "\n";

// Update the student's name
$oldName = $student->name;
$newName = "TEST UPDATED - " . $oldName;

echo "🔄 Updating student name...\n";
echo "  From: {$oldName}\n";
echo "  To: {$newName}\n\n";

$student->name = $newName;
$student->save();

echo "✅ Student model updated successfully!\n\n";

// Wait a moment for observer to process
usleep(100000); // 100ms

// Check records AFTER update
echo "📊 Checking records AFTER update:\n\n";

// Refresh the data from database
$approvalsAfter = SocialContractApproval::where('student_id', $student->student_id)->get();
$ticketsAfter = SupportTicket::where('student_id', $student->id)->get();

echo "  Social Contract Approvals: {$approvalsAfter->count()} records\n";
if ($approvalsAfter->count() > 0) {
    echo "    Sample: {$approvalsAfter->first()->student_name} ({$approvalsAfter->first()->student_id})\n";
}

echo "  Support Tickets: {$ticketsAfter->count()} records\n";
if ($ticketsAfter->count() > 0) {
    echo "    Sample: {$ticketsAfter->first()->student_name}\n";
}

echo "\n";

// Verify the updates
$allUpdated = true;
$results = [];

// Check approvals
if ($approvalsAfter->count() > 0) {
    $updatedApprovalsCount = $approvalsAfter->where('student_name', $newName)->count();
    if ($updatedApprovalsCount === $approvalsAfter->count()) {
        echo "✅ All social_contract_approvals updated successfully ({$updatedApprovalsCount}/{$approvalsAfter->count()})\n";
        $results[] = "✅ Social Contract Approvals: PASS";
    } else {
        echo "❌ Some social_contract_approvals NOT updated ({$updatedApprovalsCount}/{$approvalsAfter->count()})\n";
        $results[] = "❌ Social Contract Approvals: FAIL";
        $allUpdated = false;
    }
}

// Check tickets
if ($ticketsAfter->count() > 0) {
    $updatedTicketsCount = $ticketsAfter->where('student_name', $newName)->count();
    if ($updatedTicketsCount === $ticketsAfter->count()) {
        echo "✅ All support_tickets updated successfully ({$updatedTicketsCount}/{$ticketsAfter->count()})\n";
        $results[] = "✅ Support Tickets: PASS";
    } else {
        echo "❌ Some support_tickets NOT updated ({$updatedTicketsCount}/{$ticketsAfter->count()})\n";
        $results[] = "❌ Support Tickets: FAIL";
        $allUpdated = false;
    }
}

echo "\n";

// Restore original name
echo "🔄 Restoring original name...\n";
$student->name = $oldName;
$student->save();
echo "✅ Student name restored to: {$oldName}\n\n";

// Final summary
echo "=== TEST SUMMARY ===\n\n";
foreach ($results as $result) {
    echo "  {$result}\n";
}

echo "\n";

if ($allUpdated) {
    echo "🎉 ALL TESTS PASSED! Student information propagates correctly across the system.\n\n";
    exit(0);
} else {
    echo "⚠️  SOME TESTS FAILED! Please review the UserObserver implementation.\n\n";
    exit(1);
}
