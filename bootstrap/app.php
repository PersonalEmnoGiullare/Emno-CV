<?php

use App\Http\Middleware\RefreshTokenExpiration;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\UserRol;
use App\Http\Middleware\VerifyRequestSource;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            // registro de archivos de rutas para mayor control
            Route::middleware('web')->group(base_path('routes/emno.php'));
            Route::middleware('web')->group(base_path('routes/qr.php'));
            Route::middleware('web')->group(base_path('routes/rep_metro.php'));
            Route::middleware('api')->group(base_path('routes/qr_api.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        // registro de middleware para manejo del cors
        $middleware->append(\App\Http\Middleware\HandleCors::class);
        // registro de middleware personalizados
        $middleware->alias([
            'verify.source' => VerifyRequestSource::class,
            'refresh.token' => RefreshTokenExpiration::class,
            'rol' => UserRol::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
