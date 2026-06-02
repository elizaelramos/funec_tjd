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
        // Monitoramento de acessos e segurança (roda no terminate).
        // Global para capturar também rotas inexistentes (sondagem de .env,
        // wp-admin, etc.), que não passam pelo grupo de middleware "web".
        $middleware->append(\App\Http\Middleware\TrackAccess::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
