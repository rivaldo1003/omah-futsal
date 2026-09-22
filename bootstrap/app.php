<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Automatically bust route & config cache and remove orphaned migrations on deploy webhook
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
if (strpos($requestUri, '/deploy/execute/') !== false) {
    if (function_exists('opcache_reset')) {
        @opcache_reset();
    }
    foreach (glob(__DIR__.'/cache/*.php') as $cacheFile) {
        $basename = basename($cacheFile);
        if ($basename !== 'packages.php' && $basename !== 'services.php') {
            @unlink($cacheFile);
        }
    }
    @unlink(__DIR__.'/../database/migrations/2025_12_12_031948_create_news_articles_table.php');
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
