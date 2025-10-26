<?php

/**
 * Verification script for session keep-alive implementation
 * 
 * Run this with: php scripts/verify_keep_alive_setup.php
 */

require __DIR__ . '/../vendor/autoload.php';

echo "🔍 SESSION KEEP-ALIVE VERIFICATION\n";
echo str_repeat("=", 70) . "\n\n";

$results = [
    'passed' => 0,
    'failed' => 0,
    'warnings' => 0
];

// Check 1: KeepSessionAlive Middleware exists
echo "Check 1: KeepSessionAlive Middleware...\n";
$middlewareFile = __DIR__ . '/../app/Http/Middleware/KeepSessionAlive.php';
if (file_exists($middlewareFile)) {
    $content = file_get_contents($middlewareFile);
    if (strpos($content, 'last_keep_alive') !== false) {
        echo "✅ PASS: Middleware exists and updates last_keep_alive\n\n";
        $results['passed']++;
    } else {
        echo "⚠️  WARNING: Middleware exists but may not be updating timestamp\n\n";
        $results['warnings']++;
    }
} else {
    echo "❌ FAIL: KeepSessionAlive middleware not found\n\n";
    $results['failed']++;
}

// Check 2: Middleware registered in bootstrap/app.php
echo "Check 2: Middleware registration...\n";
$bootstrapFile = __DIR__ . '/../bootstrap/app.php';
if (file_exists($bootstrapFile)) {
    $content = file_get_contents($bootstrapFile);
    if (strpos($content, 'KeepSessionAlive::class') !== false) {
        echo "✅ PASS: Middleware registered in bootstrap/app.php\n\n";
        $results['passed']++;
    } else {
        echo "❌ FAIL: Middleware not registered in bootstrap/app.php\n\n";
        $results['failed']++;
    }
} else {
    echo "❌ FAIL: bootstrap/app.php not found\n\n";
    $results['failed']++;
}

// Check 3: Keep-alive routes exist
echo "Check 3: Keep-alive routes...\n";
$webRoutesFile = __DIR__ . '/../routes/web.php';
if (file_exists($webRoutesFile)) {
    $content = file_get_contents($webRoutesFile);
    $routes = [
        '/keep-alive' => false,
        '/api/refresh-csrf' => false,
        '/refresh-csrf' => false,
        '/api/ping' => false
    ];
    
    foreach ($routes as $route => $found) {
        if (strpos($content, "'" . $route . "'") !== false || 
            strpos($content, '"' . $route . '"') !== false) {
            echo "✅ Route exists: {$route}\n";
            $routes[$route] = true;
        } else {
            echo "⚠️  Route missing: {$route}\n";
        }
    }
    
    $foundCount = array_sum($routes);
    if ($foundCount >= 3) {
        echo "\n✅ PASS: Keep-alive routes configured ({$foundCount}/4)\n\n";
        $results['passed']++;
    } else {
        echo "\n⚠️  WARNING: Only {$foundCount}/4 keep-alive routes found\n\n";
        $results['warnings']++;
    }
} else {
    echo "❌ FAIL: routes/web.php not found\n\n";
    $results['failed']++;
}

// Check 4: Session Keeper JavaScript exists
echo "Check 4: Session Keeper JavaScript...\n";
$jsFile = __DIR__ . '/../public/js/session-keeper.js';
if (file_exists($jsFile)) {
    $content = file_get_contents($jsFile);
    $features = [
        'CSRF refresh' => 'refreshCsrfToken',
        'Session ping' => 'pingSession',
        'Visibility handling' => 'handleVisibilityChange',
        'AJAX interception' => 'interceptAjaxRequests'
    ];
    
    $foundFeatures = 0;
    foreach ($features as $name => $function) {
        if (strpos($content, $function) !== false) {
            echo "✅ {$name} implemented\n";
            $foundFeatures++;
        } else {
            echo "⚠️  {$name} missing\n";
        }
    }
    
    if ($foundFeatures === count($features)) {
        echo "\n✅ PASS: All session keeper features present\n\n";
        $results['passed']++;
    } else {
        echo "\n⚠️  WARNING: Only {$foundFeatures}/" . count($features) . " features found\n\n";
        $results['warnings']++;
    }
} else {
    echo "❌ FAIL: session-keeper.js not found\n\n";
    $results['failed']++;
}

// Check 5: Session Keeper loaded in dashboards
echo "Check 5: Session Keeper loaded in dashboards...\n";
$dashboards = [
    'student' => __DIR__ . '/../resources/views/dashboards/student.blade.php',
    'admin' => __DIR__ . '/../resources/views/dashboards/admin.blade.php',
    'super_admin' => __DIR__ . '/../resources/views/dashboards/super_admin.blade.php'
];

$loadedCount = 0;
foreach ($dashboards as $name => $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (strpos($content, 'session-keeper.js') !== false) {
            echo "✅ Loaded in {$name} dashboard\n";
            $loadedCount++;
        } else {
            echo "⚠️  Not loaded in {$name} dashboard\n";
        }
    } else {
        echo "⚠️  {$name} dashboard file not found\n";
    }
}

if ($loadedCount === count($dashboards)) {
    echo "\n✅ PASS: Session keeper loaded in all dashboards\n\n";
    $results['passed']++;
} else {
    echo "\n⚠️  WARNING: Only loaded in {$loadedCount}/" . count($dashboards) . " dashboards\n\n";
    $results['warnings']++;
}

// Check 6: Session configuration
echo "Check 6: Session configuration...\n";
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $content = file_get_contents($envFile);
    $settings = [
        'SESSION_DRIVER=database',
        'SESSION_LIFETIME=525600',
        'SESSION_SECURE_COOKIE=false',
        'SESSION_SAME_SITE=lax'
    ];
    
    $foundSettings = 0;
    foreach ($settings as $setting) {
        if (strpos($content, $setting) !== false) {
            echo "✅ {$setting}\n";
            $foundSettings++;
        } else {
            echo "⚠️  Missing: {$setting}\n";
        }
    }
    
    if ($foundSettings === count($settings)) {
        echo "\n✅ PASS: All session settings configured\n\n";
        $results['passed']++;
    } else {
        echo "\n⚠️  WARNING: Only {$foundSettings}/" . count($settings) . " settings found\n\n";
        $results['warnings']++;
    }
} else {
    echo "❌ FAIL: .env file not found\n\n";
    $results['failed']++;
}

// Summary
echo str_repeat("=", 70) . "\n";
echo "📊 SUMMARY\n";
echo str_repeat("=", 70) . "\n\n";

echo "✅ Passed:   {$results['passed']}\n";
echo "⚠️  Warnings: {$results['warnings']}\n";
echo "❌ Failed:   {$results['failed']}\n\n";

if ($results['failed'] === 0 && $results['warnings'] === 0) {
    echo "🎉 PERFECT! All keep-alive mechanisms are properly configured!\n\n";
} elseif ($results['failed'] === 0) {
    echo "✅ GOOD! Keep-alive is configured with minor warnings.\n\n";
} else {
    echo "⚠️  ISSUES FOUND! Please fix the failed checks above.\n\n";
}

echo "🔧 IMPLEMENTED MECHANISMS:\n";
echo "   1. KeepSessionAlive Middleware - Updates session on every request\n";
echo "   2. Session Keeper JS - Pings every 2 minutes, refreshes CSRF every 5 min\n";
echo "   3. Multiple keep-alive endpoints - /keep-alive, /api/ping, /refresh-csrf\n";
echo "   4. Visibility handling - Pauses when tab hidden, resumes when visible\n";
echo "   5. AJAX interception - Auto-adds CSRF token to all requests\n";
echo "   6. Page refresh handling - Pings before unload, restores on load\n\n";

echo "🎯 EXPECTED BEHAVIOR:\n";
echo "   • Sessions NEVER expire while browser tab is open\n";
echo "   • CSRF tokens auto-refresh every 5 minutes\n";
echo "   • Session pings every 2 minutes to keep alive\n";
echo "   • Works across page refreshes and browser tab switches\n";
echo "   • All forms always have valid CSRF tokens\n\n";

echo "🧪 TESTING:\n";
echo "   1. Login and leave browser tab open for 30+ minutes\n";
echo "   2. Refresh page - should stay logged in\n";
echo "   3. Switch tabs, come back - should stay logged in\n";
echo "   4. Submit forms - should work without CSRF errors\n";
echo "   5. Check browser console for SessionKeeper logs (enable debug mode)\n\n";
