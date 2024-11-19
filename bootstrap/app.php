<?php

use Illuminate\Support\Facades\Log;
use App\Http\Middleware\RoleManager;
use Illuminate\Foundation\Application;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \RealRashid\SweetAlert\ToSweetAlert::class,
            \App\Http\Middleware\UserActivity::class,
            // \App\Http\Middleware\LogUserActivity::class,
        ]);
        $middleware->alias([
            'roleManager' => RoleManager::class
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('notify:expiring-policies')->daily();
        // Log::info('This is a scheduled task!');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
