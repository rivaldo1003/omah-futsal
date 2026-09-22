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

// Automatically invalidate route & config cache and remove orphaned migrations before booting Laravel on deploy webhook
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
if (strpos($requestUri, '/api/deploy/execute/') !== false) {
    if (function_exists('opcache_reset')) {
        @opcache_reset();
    }
    foreach (glob(__DIR__.'/../bootstrap/cache/*.php') as $cacheFile) {
        $basename = basename($cacheFile);
        if ($basename !== 'packages.php' && $basename !== 'services.php') {
            @unlink($cacheFile);
        }
    }
    @unlink(__DIR__.'/../database/migrations/2025_12_12_031948_create_news_articles_table.php');
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
