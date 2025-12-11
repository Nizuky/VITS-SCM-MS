#!/usr/bin/env php
<?php

/**
 * Migration Verification Script
 * Run this to ensure all required tables exist in the database
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "===========================================\n";
echo "DATABASE MIGRATION VERIFICATION\n";
echo "===========================================\n\n";

// Required tables for the application
$requiredTables = [
    'users',
    'social_contracts',
    'social_contract_records',
    'social_contract_approvals',
    'verifications',
    'approvals',
    'transaction_logs',
    'archives',
    'super_admins',
    'admin_users',
    'superadmin_activity_logs',
    'password_resets',
    'password_reset_tokens',
    'super_admin_password_change_tokens',
    'admin_password_change_tokens',
    'student_notifications',
    'support_tickets',
    'sessions',
    'cache',
    'cache_locks',
    'migrations',
];

echo "Checking database connection...\n";
try {
    DB::connection()->getPdo();
    $dbName = DB::connection()->getDatabaseName();
    echo "✓ Connected to database: {$dbName}\n\n";
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

echo "Checking required tables...\n";
$missingTables = [];
$existingTables = [];

foreach ($requiredTables as $table) {
    if (Schema::hasTable($table)) {
        echo "  ✓ {$table}\n";
        $existingTables[] = $table;
    } else {
        echo "  ✗ {$table} (MISSING)\n";
        $missingTables[] = $table;
    }
}

echo "\n===========================================\n";
echo "SUMMARY\n";
echo "===========================================\n";
echo "Total tables required: " . count($requiredTables) . "\n";
echo "Tables found: " . count($existingTables) . "\n";
echo "Tables missing: " . count($missingTables) . "\n";

if (count($missingTables) > 0) {
    echo "\n⚠️  MISSING TABLES:\n";
    foreach ($missingTables as $table) {
        echo "   - {$table}\n";
    }
    echo "\nRun migrations to create missing tables:\n";
    echo "   php artisan migrate --force\n\n";
    exit(1);
} else {
    echo "\n✓ All required tables exist!\n\n";
    exit(0);
}
