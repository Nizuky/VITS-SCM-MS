<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Test database connectivity
Route::get('/test-db-connection', function () {
    try {
        $startTime = microtime(true);
        
        // Test basic connection
        DB::connection()->getPdo();
        $connectionTime = microtime(true) - $startTime;
        
        // Test query
        $queryStart = microtime(true);
        $result = DB::select('SELECT 1 as test');
        $queryTime = microtime(true) - $queryStart;
        
        // Test super_admins table
        $tableStart = microtime(true);
        $superAdminCount = DB::table('super_admins')->count();
        $tableTime = microtime(true) - $tableStart;
        
        // Test admin_users table  
        $adminTableStart = microtime(true);
        $adminCount = DB::table('admin_users')->count();
        $adminTableTime = microtime(true) - $adminTableStart;
        
        return response()->json([
            'status' => 'success',
            'message' => 'Database connection is working',
            'timings' => [
                'connection' => round($connectionTime * 1000, 2) . 'ms',
                'simple_query' => round($queryTime * 1000, 2) . 'ms',
                'super_admins_count' => round($tableTime * 1000, 2) . 'ms',
                'admin_users_count' => round($adminTableTime * 1000, 2) . 'ms',
            ],
            'data' => [
                'super_admins' => $superAdminCount,
                'admin_users' => $adminCount,
            ],
            'config' => [
                'host' => config('database.connections.mysql.host'),
                'database' => config('database.connections.mysql.database'),
                'timeout' => config('database.connections.mysql.options')[PDO::ATTR_TIMEOUT] ?? 'not set',
            ]
        ]);
    } catch (\PDOException $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Database connection failed',
            'error' => $e->getMessage(),
            'code' => $e->getCode(),
            'config' => [
                'host' => config('database.connections.mysql.host'),
                'database' => config('database.connections.mysql.database'),
            ]
        ], 503);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unexpected error',
            'error' => $e->getMessage(),
        ], 500);
    }
});
