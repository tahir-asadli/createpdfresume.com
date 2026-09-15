<?php

use App\Http\Middleware\Analytic;
use App\Http\Middleware\AppMiddleware;
use App\Http\Middleware\SetLocale;
use App\Mail\CrashNotification;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(prepend: [
            Analytic::class
        ]);
        $middleware->web(append: [
            SetLocale::class
        ]);
        // $middleware->web(append: [
        //     SetLocale::class
        // ], prepend: [
        //     Analytic::class
        // ]);
    
        $middleware->prepend(Analytic::class);
        // $middleware->append(SetLocale::class);
        // $middleware->prepend(SetLocale::class);
        $middleware->validateCsrfTokens(except: [
            'result',
            'successcb'
        ]);
        $middleware->trustProxies(at: '*');
        // $middleware->append(AppMiddleware::class);
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->report(function (Throwable $e) {
            $url = url()->current();
            $user = auth()->check() ? auth()->user() : null;
            if (App::environment('production')) {
                // Mail::to(config('site.adminMail'))->queue(new CrashNotification($e, $url, $user));
            } else {
                // Mail::to(config('site.adminMail'))->send(new CrashNotification($e, $url, $user));
            }
        });
    })->create();
