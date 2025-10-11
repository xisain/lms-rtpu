<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\adminMiddleware;
use App\Http\Middleware\dosenMiddleware;
use App\Http\Middleware\studentMiddleware;

return Application::configure(basePath: dirname(__DIR__))
->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'dosen' => dosenMiddleware::class,
        ]);
    })
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
