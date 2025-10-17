<?php

return [
    // Session driver (default to file for local dev)
    'driver' => env('SESSION_DRIVER', 'file'),

    // Minutes the session can remain idle before it expires
    'lifetime' => env('SESSION_LIFETIME', 120),

    // IMPORTANT: Expire session when the browser closes (so non-remembered users are logged out)
    'expire_on_close' => true,

    // Session encryption
    'encrypt' => false,

    // Session file location (for file driver)
    'files' => storage_path('framework/sessions'),

    // Database session table (for database/redis drivers)
    'table' => 'sessions',

    // Store (for redis)
    'store' => env('SESSION_STORE', null),

    // Lottery for garbage collection
    'lottery' => [2, 100],

    // Cookie name
    'cookie' => env('SESSION_COOKIE', str_replace([' ', ':'], '_', env('APP_NAME', 'laravel')).'_session'),

    // Cookie path, domain, secure, httpOnly, sameSite
    'path' => '/',
    'domain' => env('SESSION_DOMAIN', null),
    'secure' => env('SESSION_SECURE_COOKIE', false),
    'http_only' => true,
    'same_site' => env('SESSION_SAME_SITE', 'lax'),
];
