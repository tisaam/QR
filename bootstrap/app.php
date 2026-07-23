
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
    ->withMiddleware(function (Middleware $middleware) {
        // <--- ADD YOUR ALIASES RIGHT HERE --->
         $middleware->validateCsrfTokens(except: [
        'r/*/submit-review'
    ]);
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'subscription' => \App\Http\Middleware\CheckSubscription::class,
            'qr.limit' => \App\Http\Middleware\CheckQRLimit::class,
            'aicredits' => \App\Http\Middleware\CheckAICredits::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
    ?>