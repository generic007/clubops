<?php

// Suppress PHP 8.5 deprecation notices from Laravel 11 vendor files
// (PDO::MYSQL_ATTR_SSL_CA → Pdo\Mysql::ATTR_SSL_CA)
if (PHP_VERSION_ID >= 80500) {
    error_reporting(error_reporting() & ~E_DEPRECATED);
}

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'scoped' => \App\Http\Middleware\ScopedByClub::class,
            'encrypted' => \App\Http\Middleware\LoadClubEncryptionKey::class,
            'subscribed' => \App\Http\Middleware\CheckSubscription::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
