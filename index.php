<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Invalidate cache and remove orphaned migration if deploy webhook is accessed
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
if (strpos($requestUri, '/deploy/execute/') !== false) {
    if (function_exists('opcache_reset')) {
        @opcache_reset();
    }
    foreach (glob(__DIR__.'/bootstrap/cache/*.php') as $cacheFile) {
        $basename = basename($cacheFile);
        if ($basename !== 'packages.php' && $basename !== 'services.php') {
            @unlink($cacheFile);
        }
    }
    @unlink(__DIR__.'/database/migrations/2025_12_12_031948_create_news_articles_table.php');
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
$pubHtml = '/home/ofsw1241/public_html';
echo "Public HTML contents:<br>" . implode('<br>', scandir($pubHtml)) . "<br><br>";
exit(0);

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
