<?php

use App\Helpers\ApiResponse;
use Illuminate\Foundation\Application;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $exception, $request) {
            if (str_contains($request->path(), "api")) {
                return ApiResponse::unauthorized(__("responses.unauthenticated"), []);
            }
        });
    })
    ->withSchedule(function (Schedule $schedule) {
        // Close tournaments that are finished
        $schedule->command("tournament:close")->daily();

        // Delete expired reset password token
        $schedule->command("auth:clear-resets")->daily();

        // Update the tournament current price accordingly to his start date
        $schedule->command("tournamentPrice:update")->daily();

        // Delete unverified accounts that are at least 1 month old
        $schedule->command("account:purge-unverified")->monthly();

        // Clear logs files
        $schedule->command("logs:clear")->weekly();
    })
    ->create();
