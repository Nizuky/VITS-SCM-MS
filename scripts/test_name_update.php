<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\SocialContractApproval;

echo "Testing Student Name Update Observer\n";
echo "=====================================\n\n";

// Find a student with approvals
$student = User::whereHas('socialContracts', function ($query) {
    $query->whereHas('records', function ($q) {
        $q->whereHas('approval');
    });
})->first();

if (!$student) {
    echo "No student found with approval records.\n";
    exit(1);
}

echo "Found student: {$student->name} (ID: {$student->student_id})\n";

// Check current approvals
$approvalsBefore = SocialContractApproval::where('student_id', $student->student_id)->get();
echo "Found {$approvalsBefore->count()} approval records before update\n";

if ($approvalsBefore->count() > 0) {
    echo "Sample approval before: student_name = '{$approvalsBefore->first()->student_name}'\n\n";
    
    // Save original name
    $originalName = $student->name;
    
    // Update the student's name
    $newName = $originalName . " (Updated)";
    echo "Updating student name to: {$newName}\n";
    
    $student->name = $newName;
    $student->save();
    
    echo "Student saved successfully!\n\n";
    
    // Check if approvals were updated
    $approvalsAfter = SocialContractApproval::where('student_id', $student->student_id)->get();
    echo "Checking approval records after update...\n";
    echo "Sample approval after: student_name = '{$approvalsAfter->first()->student_name}'\n\n";
    
    if ($approvalsAfter->first()->student_name === $newName) {
        echo "✓ SUCCESS! Observer updated the student_name in approvals table!\n\n";
    } else {
        echo "✗ FAILED! The student_name was not updated in approvals table.\n\n";
    }
    
    // Restore original name
    echo "Restoring original name...\n";
    $student->name = $originalName;
    $student->save();
    
    echo "✓ Test completed and data restored.\n";
} else {
    echo "No approval records found for this student.\n";
}
