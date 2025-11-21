<?php

use App\Http\Middleware\CorsMiddleware;
use App\Http\Middleware\EnsureTokenIsValid;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'ensureToken' => EnsureTokenIsValid::class,
        ]);
        $middleware->getGlobalMiddleware([
            HandleCors::class,
            CorsMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Illuminate\Auth\AuthenticationException $e, $request) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unauthorized user, Please login first',
                'code'    => 401,
            ], 401);
        });
    })->create();
