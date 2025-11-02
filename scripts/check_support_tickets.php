<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Checking Support Tickets Table...\n\n";

// Get first ticket
$ticket = \App\Models\SupportTicket::first();

if (!$ticket) {
    echo "No tickets found in database\n";
    exit;
}

echo "Ticket ID: " . $ticket->id . "\n";
echo "student_id column value: " . $ticket->student_id . "\n";
echo "student_name: " . $ticket->student_name . "\n";

// Check relationship
echo "\nChecking relationship...\n";
$student = $ticket->student;

if ($student) {
    echo "Student found via relationship\n";
    echo "Student ID (from users table): " . $student->id . "\n";
    echo "Student student_id field: " . ($student->student_id ?? 'NULL') . "\n";
    echo "Student name: " . $student->name . "\n";
} else {
    echo "No student found via relationship\n";
}

echo "\n\nTesting API response format...\n";
$apiData = [
    'id' => $ticket->id,
    'student_id' => $ticket->student ? $ticket->student->student_id : 'N/A',
    'student_name' => $ticket->student_name,
    'type' => $ticket->issue_type,
];

echo "API would return:\n";
print_r($apiData);
