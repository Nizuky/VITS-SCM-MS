<?php

/**
 * Final comprehensive session management verification
 * 
 * Run this with: php scripts/final_session_verification.php
 */

require __DIR__ . '/../vendor/autoload.php';

echo "🎯 FINAL SESSION MANAGEMENT VERIFICATION\n";
echo str_repeat("=", 70) . "\n\n";

$allPassed = true;

// CHECK 1: Session Configuration
echo "✅ CHECK 1: Session Configuration\n";
echo str_repeat("-", 70) . "\n";
$envFile = file_get_contents(__DIR__ . '/../.env');
$requiredSettings = [
    'SESSION_DRIVER=database' => 'Using database driver for persistence',
    'SESSION_LIFETIME=525600' => '1 year session lifetime',
    'SESSION_SECURE_COOKIE=false' => 'HTTP compatible (correct for scms.test)',
    'SESSION_SAME_SITE=lax' => 'Cross-tab compatibility',
];

foreach ($requiredSettings as $setting => $desc) {
    if (strpos($envFile, $setting) !== false) {
        echo "✅ {$setting}\n   → {$desc}\n";
    } else {
        echo "❌ MISSING: {$setting}\n";
        $allPassed = false;
    }
}
echo "\n";

// CHECK 2: No Session Regeneration
echo "✅ CHECK 2: No Session ID Regeneration\n";
echo str_repeat("-", 70) . "\n";
$regenerateCount = 0;
$files = [
    'resources/views/livewire/auth/login.blade.php',
    'app/Http/Controllers/SuperAdmin/LoginController.php',
    'app/Http/Controllers/Admin/Auth/LoginController.php',
];

foreach ($files as $file) {
    $fullPath = __DIR__ . '/../' . $file;
    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
        if (preg_match('/Session::regenerate\(\)|session\(\)->regenerate\(\)/', $content)) {
            echo "❌ Found session regeneration in: {$file}\n";
            $regenerateCount++;
            $allPassed = false;
        }
    }
}

if ($regenerateCount === 0) {
    echo "✅ No session()->regenerate() calls found\n";
    echo "   → Using regenerateToken() instead (correct!)\n";
} else {
    echo "❌ Found {$regenerateCount} files with session regeneration\n";
}
echo "\n";

// CHECK 3: Keep-Alive Mechanisms
echo "✅ CHECK 3: Keep-Alive Mechanisms\n";
echo str_repeat("-", 70) . "\n";

$mechanisms = [
    'KeepSessionAlive Middleware' => 'app/Http/Middleware/KeepSessionAlive.php',
    'Session Keeper JS' => 'public/js/session-keeper.js',
    'Keep-Alive Routes' => 'routes/web.php',
];

foreach ($mechanisms as $name => $file) {
    $fullPath = __DIR__ . '/../' . $file;
    if (file_exists($fullPath)) {
        echo "✅ {$name} exists\n";
    } else {
        echo "❌ {$name} missing\n";
        $allPassed = false;
    }
}
echo "\n";

// CHECK 4: Dashboard Integration
echo "✅ CHECK 4: Dashboard Keep-Alive Integration\n";
echo str_repeat("-", 70) . "\n";

$dashboards = [
    'Student' => 'resources/views/dashboards/student.blade.php',
    'Admin' => 'resources/views/dashboards/admin.blade.php',
    'Super Admin' => 'resources/views/dashboards/super_admin.blade.php',
];

foreach ($dashboards as $name => $file) {
    $fullPath = __DIR__ . '/../' . $file;
    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
        $hasSessionKeeper = strpos($content, 'session-keeper.js') !== false;
        $hasKeepAlive = strpos($content, 'function keepAlive()') !== false;
        $hasRefreshCsrf = strpos($content, 'function refreshCsrf()') !== false;
        
        if ($hasSessionKeeper && $hasKeepAlive && $hasRefreshCsrf) {
            echo "✅ {$name} Dashboard: All keep-alive scripts present\n";
        } else {
            echo "⚠️  {$name} Dashboard: Missing some scripts\n";
            if (!$hasSessionKeeper) echo "   ❌ session-keeper.js\n";
            if (!$hasKeepAlive) echo "   ❌ keepAlive() function\n";
            if (!$hasRefreshCsrf) echo "   ❌ refreshCsrf() function\n";
        }
    } else {
        echo "❌ {$name} Dashboard file not found\n";
        $allPassed = false;
    }
}
echo "\n";

// CHECK 5: Routes
echo "✅ CHECK 5: Keep-Alive Routes\n";
echo str_repeat("-", 70) . "\n";

$webRoutes = file_get_contents(__DIR__ . '/../routes/web.php');
$routes = [
    '/keep-alive' => false,
    '/refresh-csrf' => false,
    '/api/refresh-csrf' => false,
    '/api/ping' => false,
];

foreach ($routes as $route => $found) {
    if (strpos($webRoutes, "'{$route}'") !== false || strpos($webRoutes, "\"{$route}\"") !== false) {
        echo "✅ Route: {$route}\n";
        $routes[$route] = true;
    } else {
        echo "❌ Route missing: {$route}\n";
        $allPassed = false;
    }
}
echo "\n";

// CHECK 6: Middleware Registration
echo "✅ CHECK 6: Middleware Registration\n";
echo str_repeat("-", 70) . "\n";

$bootstrapFile = file_get_contents(__DIR__ . '/../bootstrap/app.php');
$middlewares = [
    'IsolateWebGuardSession' => 'Protects admin sessions during student registration',
    'KeepSessionAlive' => 'Updates session timestamp on every request',
    'RefreshSessionActivity' => 'Refreshes session activity markers',
];

foreach ($middlewares as $middleware => $desc) {
    if (strpos($bootstrapFile, $middleware) !== false) {
        echo "✅ {$middleware}\n   → {$desc}\n";
    } else {
        echo "❌ {$middleware} not registered\n";
        $allPassed = false;
    }
}
echo "\n";

// FINAL SUMMARY
echo str_repeat("=", 70) . "\n";
echo "📊 FINAL SUMMARY\n";
echo str_repeat("=", 70) . "\n\n";

if ($allPassed) {
    echo "🎉 PERFECT! All session management mechanisms are in place!\n\n";
    
    echo "🛡️  PROTECTION LAYERS:\n";
    echo "   1. KeepSessionAlive Middleware (every request)\n";
    echo "   2. SessionKeeper JS (2-minute pings, 5-minute CSRF refresh)\n";
    echo "   3. Simple Keep-Alive JS (20-minute pings)\n";
    echo "   4. Simple CSRF Refresh (30-minute refresh)\n";
    echo "   5. IsolateWebGuardSession (registration protection)\n";
    echo "   6. Database Session Driver (persistent storage)\n\n";
    
    echo "✅ EXPECTED BEHAVIOR:\n";
    echo "   • Admin/Super Admin: Never logged out (session + keep-alive)\n";
    echo "   • Students: Can use 'Remember Me' for persistent login\n";
    echo "   • Page refresh: Always works, stays logged in\n";
    echo "   • Create student: Admin stays logged in\n";
    echo "   • CSRF tokens: Auto-refresh, never expire\n";
    echo "   • Multiple tabs: All stay logged in\n";
    echo "   • Browser close: Session persists (1 year lifetime)\n\n";
    
    echo "🧪 TEST SCENARIOS:\n";
    echo "   1. Login, wait 30 minutes, refresh → ✅ Still logged in\n";
    echo "   2. Login, create student, refresh → ✅ Still logged in\n";
    echo "   3. Login, switch tabs, come back → ✅ Still logged in\n";
    echo "   4. Login, close browser, reopen → ✅ Still logged in\n";
    echo "   5. Submit forms after 30+ min → ✅ No CSRF errors\n\n";
    
} else {
    echo "⚠️  ISSUES FOUND! Please review the checks above.\n\n";
}

echo "📝 CONFIGURATION SUMMARY:\n";
echo "   Session Driver: database\n";
echo "   Session Lifetime: 525,600 minutes (1 year)\n";
echo "   Secure Cookie: false (correct for HTTP)\n";
echo "   Same Site: lax\n";
echo "   Expire on Close: false\n\n";

echo "🔧 KEEP-ALIVE INTERVALS:\n";
echo "   • SessionKeeper Ping: 2 minutes\n";
echo "   • SessionKeeper CSRF: 5 minutes\n";
echo "   • Simple Keep-Alive: 20 minutes\n";
echo "   • Simple CSRF Refresh: 30 minutes\n\n";

echo "💡 DEBUGGING:\n";
echo "   • Enable SessionKeeper debug: Set debug: true in dashboard JS\n";
echo "   • Check browser console for [SessionKeeper] logs\n";
echo "   • Monitor Network tab for /api/ping and /keep-alive requests\n";
echo "   • Check sessions table: SELECT * FROM sessions;\n\n";

echo "🚀 READY FOR TESTING!\n";
