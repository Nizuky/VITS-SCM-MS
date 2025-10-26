<?php

/**
 * Test script to verify session isolation is working correctly
 * 
 * Run this with: php scripts/test_session_isolation.php
 * 
 * This will simulate the scenario where:
 * 1. A super admin is logged in
 * 2. A new student account is created (which Fortify tries to auto-login)
 * 3. Verify the super admin stays logged in
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\SuperAdmin;
use App\Models\User;

echo "🔍 SESSION ISOLATION TEST\n";
echo str_repeat("=", 50) . "\n\n";

// Step 1: Simulate super admin login
echo "Step 1: Logging in as Super Admin...\n";
$superAdmin = SuperAdmin::first();
if (!$superAdmin) {
    echo "❌ ERROR: No super admin found in database\n";
    exit(1);
}

Auth::guard('superadmin')->login($superAdmin, true);
echo "✅ Super Admin logged in: {$superAdmin->email}\n";
echo "   Guard check: " . (Auth::guard('superadmin')->check() ? 'AUTHENTICATED' : 'NOT AUTHENTICATED') . "\n";
echo "   User ID: " . Auth::guard('superadmin')->id() . "\n\n";

// Step 2: Simulate student creation (like Fortify does during registration)
echo "Step 2: Creating new student account...\n";
$timestamp = substr(time(), -6); // Last 6 digits
$studentEmail = 'test_' . $timestamp . '@student.example.com';
$student = User::create([
    'student_id' => 'T' . $timestamp, // Shorter student ID
    'name' => 'Test Student',
    'email' => $studentEmail,
    'password' => bcrypt('password123'),
    'verification_code' => null,
    'verified_at' => now(),
]);
echo "✅ Student created: {$student->email}\n\n";

// Step 3: Simulate Fortify's auto-login
echo "Step 3: Simulating Fortify auto-login (logging in student on web guard)...\n";
Auth::guard('web')->login($student, false);
echo "✅ Student logged in on web guard\n";
echo "   Web guard check: " . (Auth::guard('web')->check() ? 'AUTHENTICATED' : 'NOT AUTHENTICATED') . "\n";
echo "   Web guard user: " . (Auth::guard('web')->user() ? Auth::guard('web')->user()->email : 'NONE') . "\n\n";

// Step 4: Verify super admin is STILL logged in
echo "Step 4: Checking if Super Admin session was affected...\n";
$stillLoggedIn = Auth::guard('superadmin')->check();
$currentUserId = Auth::guard('superadmin')->id();

if ($stillLoggedIn && $currentUserId === $superAdmin->id) {
    echo "✅ SUCCESS! Super Admin is still logged in\n";
    echo "   Guard check: AUTHENTICATED\n";
    echo "   User ID: {$currentUserId}\n";
    echo "   Email: " . Auth::guard('superadmin')->user()->email . "\n\n";
    echo "🎉 SESSION ISOLATION IS WORKING!\n";
} else {
    echo "❌ FAILURE! Super Admin was logged out\n";
    echo "   Guard check: " . ($stillLoggedIn ? 'AUTHENTICATED' : 'NOT AUTHENTICATED') . "\n";
    echo "   Current ID: " . ($currentUserId ?? 'NULL') . "\n\n";
    echo "⚠️  SESSION ISOLATION FAILED - admin/superadmin sessions are being affected\n";
}

echo "\n" . str_repeat("=", 50) . "\n";

// Cleanup
echo "\nCleaning up test data...\n";
$student->delete();
Auth::guard('web')->logout();
Auth::guard('superadmin')->logout();
echo "✅ Cleanup complete\n";
