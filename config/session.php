<?php

return [
    // Session driver - defaults to 'file' for reliability
    // Set SESSION_DRIVER=database in production when database is confirmed working
    'driver' => env('SESSION_DRIVER', 'file'),

    // Minutes the session can remain idle before it expires
    // Set to 1 year (525600 minutes) but session keeper will ping to keep alive
    'lifetime' => env('SESSION_LIFETIME', 525600),

    // Set to false so sessions persist across browser close
    // Sessions will only expire after 'lifetime' minutes of inactivity
    'expire_on_close' => false,

    // Session encryption (false for better performance)
    'encrypt' => false,

    // Session file location (for file driver)
    'files' => storage_path('framework/sessions'),

    // Database session table (for database/redis drivers)
    'table' => 'sessions',

    // Store (for redis)
    'store' => env('SESSION_STORE', null),

    // Lottery for garbage collection (run less frequently)
    'lottery' => [2, 100],

    // Cookie name
    'cookie' => env('SESSION_COOKIE', str_replace([' ', ':'], '_', env('APP_NAME', 'laravel')).'_session'),

    // Cookie path, domain, secure, httpOnly, sameSite
    'path' => '/',
    'domain' => env('SESSION_DOMAIN', null),
    'secure' => env('SESSION_SECURE_COOKIE', false),
    'http_only' => true,
    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    // Partitioned cookies (for Chrome's CHIPS)
    'partitioned' => false,
];
