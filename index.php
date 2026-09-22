<?php

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

// Ensure environment and storage directories exist
if (!file_exists(__DIR__.'/.env') && file_exists('/home/ofsw1241/laravel/.env')) {
    @copy('/home/ofsw1241/laravel/.env', __DIR__.'/.env');
}
@mkdir(__DIR__.'/storage/framework/views', 0755, true);
@mkdir(__DIR__.'/storage/framework/cache/data', 0755, true);
@mkdir(__DIR__.'/storage/framework/sessions', 0755, true);
@mkdir(__DIR__.'/storage/logs', 0755, true);
@mkdir(__DIR__.'/bootstrap/cache', 0755, true);

// Recreate public/storage symlink if it doesn't exist or isn't a link
// On cPanel: public_html/public/storage -> /home/ofsw1241/laravel/storage/app/public
$storageLinkTarget = __DIR__.'/public/storage';
$laravelStoragePublic = '/home/ofsw1241/laravel/storage/app/public';
$localStoragePublic   = __DIR__.'/storage/app/public';

if (!is_link($storageLinkTarget) && !is_dir($storageLinkTarget)) {
    if (is_dir($laravelStoragePublic)) {
        @symlink($laravelStoragePublic, $storageLinkTarget);
    } elseif (is_dir($localStoragePublic)) {
        @symlink($localStoragePublic, $storageLinkTarget);
    }
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
// Autoloader: use local vendor if exists, fallback to /home/ofsw1241/laravel/vendor
if (file_exists(__DIR__.'/vendor/autoload.php')) {
    require __DIR__.'/vendor/autoload.php';
} elseif (file_exists('/home/ofsw1241/laravel/vendor/autoload.php')) {
    require '/home/ofsw1241/laravel/vendor/autoload.php';
} else {
    die('Autoloader not found');
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
