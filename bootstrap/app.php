<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\TeacherMiddleware;
use App\Http\Middleware\ManagerMiddleware;
use App\Http\Middleware\ContentManagerMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckAdvancedLevelMiddleware;
use App\Http\Middleware\CheckBannedMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ⬇️ Применяем middleware к группе 'web'
        $middleware->web([
            CheckBannedMiddleware::class,
        ]);

        // ⬇️ Регистрируем псевдонимы middleware
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'teacher' => TeacherMiddleware::class,
            'admin' => AdminMiddleware::class,
            'content_manager' => ContentManagerMiddleware::class,
            'manager' => ManagerMiddleware::class,
            'advanced' => CheckAdvancedLevelMiddleware::class,
            'banned' => CheckBannedMiddleware::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
