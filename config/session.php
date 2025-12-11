<?php

return [
    // Session driver - use 'cookie' for Laravel Cloud (stateless), 'file' for local
    'driver' => env('SESSION_DRIVER', 'cookie'),

    // Minutes the session can remain idle before it expires (1 year)
    'lifetime' => env('SESSION_LIFETIME', 525600),

    // Set to false so sessions persist across browser close
    'expire_on_close' => false,

    // Session encryption (true for cookie driver security)
    'encrypt' => true,

    // Session file location (for file driver)
    'files' => storage_path('framework/sessions'),

    // Database session table (for database/redis drivers)
    'table' => 'sessions',

    // Store (for redis)
    'store' => env('SESSION_STORE', null),

    // Lottery for garbage collection
    'lottery' => [2, 100],

    // Cookie name - unique to avoid conflicts
    'cookie' => env('SESSION_COOKIE', 'vits_scms_session'),

    // Cookie path - root path
    'path' => '/',
    
    // Domain - null allows cookie to work on any subdomain
    // For Laravel Cloud, leave as null or set to the exact domain
    'domain' => env('SESSION_DOMAIN', null),
    
    // Secure cookie - MUST be true for HTTPS sites (Laravel Cloud uses HTTPS)
    'secure' => env('SESSION_SECURE_COOKIE', true),
    
    // HTTP only - prevents JavaScript access to session cookie
    'http_only' => true,
    
    // SameSite - 'lax' allows normal navigation, 'none' required for cross-site
    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    // Partitioned cookies (for Chrome's CHIPS)
    'partitioned' => false,
];
