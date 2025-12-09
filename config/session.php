<?php

return [
    // Session driver - use 'cookie' for Laravel Cloud, 'file' for local
    'driver' => env('SESSION_DRIVER', 'cookie'),

    // Minutes the session can remain idle before it expires
    'lifetime' => env('SESSION_LIFETIME', 120),

    // Set to false so sessions persist across browser close
    'expire_on_close' => false,

    // Session encryption (true for cookie driver security)
    'encrypt' => env('SESSION_DRIVER', 'cookie') === 'cookie',

    // Session file location (for file driver)
    'files' => storage_path('framework/sessions'),

    // Database session table (for database/redis drivers)
    'table' => 'sessions',

    // Store (for redis)
    'store' => env('SESSION_STORE', null),

    // Lottery for garbage collection
    'lottery' => [2, 100],

    // Cookie name
    'cookie' => env('SESSION_COOKIE', 'vits_session'),

    // Cookie path, domain, secure, httpOnly, sameSite
    'path' => '/',
    'domain' => env('SESSION_DOMAIN', null),
    'secure' => env('SESSION_SECURE_COOKIE', null),
    'http_only' => true,
    'same_site' => 'lax',

    // Partitioned cookies (for Chrome's CHIPS)
    'partitioned' => false,
];
