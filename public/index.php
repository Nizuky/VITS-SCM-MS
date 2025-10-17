<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$response = null;

// Use the standard Http Kernel. Fail fast if it's not available to enforce a consistent bootstrap.
if ($app->bound(Illuminate\Contracts\Http\Kernel::class)) {
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

    $response = $kernel->handle($request = Request::capture());

    $response->send();

    $kernel->terminate($request, $response);
} else {
    // Log an explicit error to the PHP error log to aid debugging in environments
    // where the Http Kernel binding is missing, then fail fast.
    $message = sprintf("[%s] Missing HTTP Kernel binding: unable to process request in %s", date('c'), __FILE__);
    error_log($message);

    throw new \RuntimeException('No HTTP Kernel available to process the request. Check bootstrap/app.php and config.');
}
