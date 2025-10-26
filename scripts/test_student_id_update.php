<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\SocialContractApproval;

echo "Testing Student ID Update Observer\n";
echo "===================================\n\n";

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
    echo "Sample approval before: student_id = '{$approvalsBefore->first()->student_id}'\n\n";
    
    // Save original student_id
    $originalStudentId = $student->student_id;
    
    // Update the student's student_id (temporarily change it)
    $newStudentId = "99-9999";
    echo "Updating student_id to: {$newStudentId}\n";
    
    $student->student_id = $newStudentId;
    $student->save();
    
    echo "Student saved successfully!\n\n";
    
    // Check if approvals were updated
    $approvalsAfter = SocialContractApproval::where('student_id', $newStudentId)->get();
    echo "Checking approval records after update...\n";
    echo "Found {$approvalsAfter->count()} records with new student_id\n";
    
    if ($approvalsAfter->count() === $approvalsBefore->count()) {
        echo "Sample approval after: student_id = '{$approvalsAfter->first()->student_id}'\n\n";
        echo "✓ SUCCESS! Observer updated the student_id in approvals table!\n\n";
    } else {
        echo "✗ FAILED! The student_id was not updated in approvals table.\n\n";
    }
    
    // Restore original student_id
    echo "Restoring original student_id...\n";
    $student->student_id = $originalStudentId;
    $student->save();
    
    echo "✓ Test completed and data restored.\n";
} else {
    echo "No approval records found for this student.\n";
}
