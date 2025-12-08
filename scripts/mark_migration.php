<?php
// Usage: php scripts/mark_migration.php 2025_10_11_000001_create_users_table

if ($argc < 2) {
    echo "Usage: php scripts/mark_migration.php <migration_name>\n";
    exit(1);
}
$migration = $argv[1];

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$db = $app['db'];

try {
    $exists = $db->table('migrations')->where('migration', $migration)->exists();
    if ($exists) {
        echo "Migration {$migration} already recorded in migrations table.\n";
        exit(0);
    }

    $max = $db->table('migrations')->max('batch');
    $batch = ($max === null) ? 1 : ($max + 1);

    $db->table('migrations')->insert([
        'migration' => $migration,
        'batch' => $batch,
    ]);

    echo "Inserted migration {$migration} with batch {$batch}.\n";
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
