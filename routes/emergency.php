<?php

use Illuminate\Support\Facades\Route;

// Emergency diagnostic page - NO DATABASE REQUIRED
Route::get('/emergency-diagnostic', function () {
    $diagnostics = [];
    
    // Environment check
    $diagnostics['environment'] = [
        'APP_ENV' => env('APP_ENV', 'NOT SET'),
        'APP_DEBUG' => env('APP_DEBUG', 'NOT SET'),
        'APP_URL' => env('APP_URL', 'NOT SET'),
    ];
    
    // Database configuration
    $diagnostics['database_env'] = [
        'DB_CONNECTION' => env('DB_CONNECTION', 'NOT SET'),
        'DB_HOST' => env('DB_HOST', 'NOT SET'),
        'DB_PORT' => env('DB_PORT', 'NOT SET'),
        'DB_DATABASE' => env('DB_DATABASE', 'NOT SET'),
        'DB_USERNAME' => env('DB_USERNAME', 'NOT SET'),
        'DB_PASSWORD' => env('DB_PASSWORD') ? '***SET***' : 'NOT SET',
        'DB_TIMEOUT' => env('DB_TIMEOUT', 'NOT SET'),
    ];
    
    // Loaded configuration
    $dbConfig = config('database.connections.mysql');
    $diagnostics['database_config'] = [
        'host' => $dbConfig['host'] ?? 'NOT SET',
        'port' => $dbConfig['port'] ?? 'NOT SET',
        'database' => $dbConfig['database'] ?? 'NOT SET',
        'username' => $dbConfig['username'] ?? 'NOT SET',
    ];
    
    // DNS test
    $dbHost = env('DB_HOST');
    if ($dbHost && $dbHost !== 'NOT SET') {
        $ip = gethostbyname($dbHost);
        $diagnostics['dns'] = [
            'hostname' => $dbHost,
            'resolved_ip' => $ip,
            'dns_works' => $ip !== $dbHost,
        ];
    } else {
        $diagnostics['dns'] = ['error' => 'DB_HOST not set'];
    }
    
    // Expected vs Actual
    $expectedHost = 'db-a08bd7aa-1588-4461-97c3-bde906d54852.ap-southeast-1.public.db.laravel.cloud';
    $expectedDatabase = 'Cloud - vits_scm_ms';
    $expectedUsername = 'jsthylbkmmff6jnv';
    
    $diagnostics['comparison'] = [
        'expected_db_host' => $expectedHost,
        'actual_db_host' => env('DB_HOST', 'NOT SET'),
        'host_matches' => env('DB_HOST') === $expectedHost,
        'expected_db_database' => $expectedDatabase,
        'actual_db_database' => env('DB_DATABASE', 'NOT SET'),
        'database_matches' => env('DB_DATABASE') === $expectedDatabase,
        'expected_db_username' => $expectedUsername,
        'actual_db_username' => env('DB_USERNAME', 'NOT SET'),
        'username_matches' => env('DB_USERNAME') === $expectedUsername,
    ];
    
    // Connection test (with timeout)
    $diagnostics['connection_test'] = ['status' => 'not_attempted'];
    try {
        $start = microtime(true);
        $pdo = new PDO(
            "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']}",
            $dbConfig['username'],
            $dbConfig['password'],
            [
                PDO::ATTR_TIMEOUT => 2,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
        $elapsed = round((microtime(true) - $start) * 1000, 2);
        $diagnostics['connection_test'] = [
            'status' => 'success',
            'time_ms' => $elapsed,
        ];
    } catch (\PDOException $e) {
        $elapsed = round((microtime(true) - $start) * 1000, 2);
        $diagnostics['connection_test'] = [
            'status' => 'failed',
            'time_ms' => $elapsed,
            'error' => $e->getMessage(),
            'error_code' => $e->getCode(),
        ];
    }
    
    // Return as HTML for easy reading
    $html = '<!DOCTYPE html>
<html>
<head>
    <title>Emergency Database Diagnostic</title>
    <style>
        body { font-family: monospace; background: #1a1a1a; color: #fff; padding: 20px; }
        h1 { color: #ff6b6b; }
        h2 { color: #4ecdc4; margin-top: 30px; }
        .success { color: #51cf66; }
        .error { color: #ff6b6b; }
        .warning { color: #ffd43b; }
        .info { color: #74c0fc; }
        pre { background: #2d2d2d; padding: 15px; border-radius: 5px; overflow-x: auto; }
        .key { color: #ffd43b; }
        .value { color: #51cf66; }
        .status-box { background: #2d2d2d; padding: 15px; border-radius: 5px; margin: 10px 0; border-left: 4px solid; }
        .status-box.success { border-color: #51cf66; }
        .status-box.error { border-color: #ff6b6b; }
        .status-box.warning { border-color: #ffd43b; }
    </style>
</head>
<body>
    <h1>🚨 Emergency Database Diagnostic</h1>
    <p>This page does NOT require database connection - safe to view during outages</p>
    ';
    
    foreach ($diagnostics as $section => $data) {
        $html .= '<h2>' . ucwords(str_replace('_', ' ', $section)) . '</h2>';
        $html .= '<pre>' . json_encode($data, JSON_PRETTY_PRINT) . '</pre>';
    }
    
    // Critical issues
    $html .= '<h2>Critical Issues Detected</h2>';
    $issues = [];
    
    if (env('DB_HOST') === 'NOT SET' || empty(env('DB_HOST'))) {
        $issues[] = '❌ DB_HOST is NOT SET in environment variables';
    } elseif (env('DB_HOST') === 'localhost' || env('DB_HOST') === '127.0.0.1') {
        $issues[] = '❌ DB_HOST is set to localhost (will not work in cloud)';
    } elseif (env('DB_HOST') !== $expectedHost) {
        $issues[] = '⚠️ DB_HOST does not match expected value';
    }
    
    if ($diagnostics['connection_test']['status'] === 'failed') {
        $issues[] = '❌ Cannot connect to database: ' . ($diagnostics['connection_test']['error'] ?? 'Unknown error');
    }
    
    if (!empty($issues)) {
        foreach ($issues as $issue) {
            $class = strpos($issue, '❌') !== false ? 'error' : 'warning';
            $html .= '<div class="status-box ' . $class . '">' . htmlspecialchars($issue) . '</div>';
        }
    } else {
        $html .= '<div class="status-box success">✅ No critical issues detected</div>';
    }
    
    // Fix instructions
    $html .= '<h2>How to Fix</h2>';
    $html .= '<div class="status-box warning">';
    $html .= '<strong>1. Go to Laravel Cloud Dashboard</strong><br>';
    $html .= '<strong>2. Navigate to: Your App → Environment Variables</strong><br>';
    $html .= '<strong>3. Set these EXACT values:</strong><br>';
    $html .= '<code style="background: #1a1a1a; padding: 5px; display: block; margin: 10px 0;">';
    $html .= 'DB_HOST=db-a08bd7aa-1588-4461-97c3-bde906d54852.ap-southeast-1.public.db.laravel.cloud<br>';
    $html .= 'DB_PORT=3306<br>';
    $html .= 'DB_DATABASE=Cloud - vits_scm_ms<br>';
    $html .= 'DB_USERNAME=jsthylbkmmff6jnv<br>';
    $html .= 'DB_PASSWORD=QXkqWoO9xir8FToisMWb<br>';
    $html .= 'DB_TIMEOUT=5';
    $html .= '</code>';
    $html .= '<strong style="color: #ff6b6b;">⚠️ CRITICAL:</strong><br>';
    $html .= '- Host ends in <strong>...7aa...</strong> NOT ...7ae...<br>';
    $html .= '- Username has lowercase L and F: jsthy<strong>l</strong>bkmm<strong>ff</strong>6jnv<br>';
    $html .= '- Password has capital W: QXkq<strong>W</strong>oO9xir8FToisM<strong>W</strong>b<br>';
    $html .= '- Database name has a SPACE: Cloud - vits_scm_ms<br><br>';
    $html .= '<strong>5. Redeploy the application</strong>';
    $html .= '</div>';
    
    $html .= '<p style="margin-top: 30px; color: #666;">Generated at: ' . date('Y-m-d H:i:s T') . '</p>';
    $html .= '</body></html>';
    
    return response($html, 200, ['Content-Type' => 'text/html']);
});
