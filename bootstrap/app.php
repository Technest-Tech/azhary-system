<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth' => \App\Http\Middleware\RedirectIfNotAuthenticated::class,
            'auth.admin' => \App\Http\Middleware\AdminAuth::class,
            'auth.teacher' => \App\Http\Middleware\TeacherAuth::class,
            'locale' => \App\Http\Middleware\SetLocale::class,
        ]);
        
        // Apply SetLocale middleware globally to all web routes
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
