<?php

use Illuminate\Support\Str;

return [
    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                // Pass SSL CA to PDO when provided (useful for Aiven TLS connections)
                defined('PDO::MYSQL_ATTR_SSL_CA') ? PDO::MYSQL_ATTR_SSL_CA : null => env('DB_SSL_CA') ?: null,
                // Set connection timeout to 5 seconds for faster failure detection
                // This fails fast and allows retry logic to kick in
                PDO::ATTR_TIMEOUT => env('DB_TIMEOUT', 5),
                // MySQL specific connection timeout (5 seconds for fast failure)
                defined('PDO::MYSQL_ATTR_CONNECT_TIMEOUT') ? PDO::MYSQL_ATTR_CONNECT_TIMEOUT : null => 5,
                // MySQL specific read timeout (10 seconds for queries)
                defined('PDO::MYSQL_ATTR_READ_TIMEOUT') ? PDO::MYSQL_ATTR_READ_TIMEOUT : null => 10,
                // MySQL specific write timeout (10 seconds for writes)
                defined('PDO::MYSQL_ATTR_WRITE_TIMEOUT') ? PDO::MYSQL_ATTR_WRITE_TIMEOUT : null => 10,
                // Set persistent connections off to avoid stale connections
                PDO::ATTR_PERSISTENT => false,
                // Enable error mode
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]) : [],
        ],
    ],

    'migrations' => 'migrations',
    'redis' => [
        'client' => env('REDIS_CLIENT', 'phpredis'),
        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_DB', 0),
        ],
    ],
];
