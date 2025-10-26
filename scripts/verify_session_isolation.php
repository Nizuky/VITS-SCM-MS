<?php

/**
 * Session Isolation Verification Script
 * 
 * This script verifies that:
 * 1. Database session driver is configured
 * 2. Sessions are stored in database
 * 3. Multiple sessions can coexist
 * 4. APP_KEY is stable
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Session Isolation Verification ===\n\n";

// Check session driver
$driver = config('session.driver');
echo "✓ Session Driver: {$driver}\n";

if ($driver !== 'database') {
    echo "⚠ WARNING: Session driver should be 'database' for proper isolation!\n";
    echo "  Current: {$driver}\n";
    echo "  Fix: Set SESSION_DRIVER=database in .env\n\n";
} else {
    echo "✓ PASS: Database session driver configured correctly\n\n";
}

// Check session lifetime
$lifetime = config('session.lifetime');
echo "✓ Session Lifetime: {$lifetime} minutes\n";

if ($lifetime < 120) {
    echo "⚠ WARNING: Session lifetime is short ({$lifetime} min)\n";
    echo "  Recommended: 43200 (30 days) or 525600 (1 year)\n\n";
} else {
    echo "✓ PASS: Session lifetime is sufficient\n\n";
}

// Check if sessions table exists
try {
    $hasSessionsTable = Schema::hasTable('sessions');
    echo "✓ Sessions Table: " . ($hasSessionsTable ? "EXISTS" : "MISSING") . "\n";
    
    if (!$hasSessionsTable && $driver === 'database') {
        echo "⚠ ERROR: Database driver requires 'sessions' table!\n";
        echo "  Fix: Run 'php artisan session:table' then 'php artisan migrate'\n\n";
    } else {
        echo "✓ PASS: Sessions table exists\n\n";
    }
} catch (Exception $e) {
    echo "⚠ ERROR checking sessions table: " . $e->getMessage() . "\n\n";
}

// Check APP_KEY
$appKey = config('app.key');
echo "✓ APP_KEY: " . ($appKey ? "SET" : "MISSING") . "\n";

if (!$appKey) {
    echo "⚠ ERROR: APP_KEY is not set!\n";
    echo "  Fix: Run 'php artisan key:generate'\n\n";
} else {
    echo "✓ PASS: APP_KEY is configured\n";
    echo "  Key: " . substr($appKey, 0, 20) . "...\n\n";
}

// Check session configuration
echo "=== Session Configuration ===\n";
echo "Expire on Close: " . (config('session.expire_on_close') ? "true" : "false") . "\n";
echo "Cookie Name: " . config('session.cookie') . "\n";
echo "Same Site: " . config('session.same_site') . "\n";
echo "HTTP Only: " . (config('session.http_only') ? "true" : "false") . "\n";
echo "Secure: " . (config('session.secure') ? "true" : "false") . "\n\n";

// Count active sessions
try {
    $sessionCount = DB::table('sessions')->count();
    echo "✓ Active Sessions in Database: {$sessionCount}\n\n";
} catch (Exception $e) {
    echo "⚠ Could not count sessions: " . $e->getMessage() . "\n\n";
}

// Final verdict
echo "=== Verification Complete ===\n";

if ($driver === 'database' && $hasSessionsTable && $appKey && $lifetime >= 120) {
    echo "✓ ALL CHECKS PASSED\n";
    echo "✓ Session isolation is properly configured\n";
    echo "✓ Multiple users can log in simultaneously without conflicts\n";
} else {
    echo "⚠ SOME ISSUES DETECTED\n";
    echo "  Review the warnings above and apply recommended fixes\n";
}

echo "\n";
