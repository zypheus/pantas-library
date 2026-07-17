<?php

use Illuminate\Console\Scheduling\Schedule;
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
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \App\Http\Middleware\ApplyFeatureFlags::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('attendance:close-stale-ins')
            ->dailyAt('00:05')
            ->timezone('Asia/Manila');

        $schedule->command('reservations:expire')
            ->hourly()
            ->timezone('Asia/Manila');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
