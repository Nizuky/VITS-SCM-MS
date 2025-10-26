<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "Checking users table...\n";
echo "Total users: " . User::count() . "\n\n";

$users = User::select('id', 'name', 'student_id', 'email', 'email_verified_at')
    ->orderBy('name')
    ->get();

if ($users->isEmpty()) {
    echo "No users found in the database.\n";
} else {
    echo "Users list:\n";
    echo str_repeat("-", 80) . "\n";
    foreach ($users as $user) {
        $verified = $user->email_verified_at ? '✓ Verified' : '✗ Not verified';
        echo sprintf(
            "ID: %-3s | %-30s | %-10s | %-30s | %s\n",
            $user->id,
            $user->name,
            $user->student_id ?? 'N/A',
            $user->email,
            $verified
        );
    }
    echo str_repeat("-", 80) . "\n";
}
