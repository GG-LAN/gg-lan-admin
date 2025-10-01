<?php

use App\Helpers\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Sentry\Laravel\Integration;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Sentry exceptions catching
        Integration::handles($exceptions);

        $exceptions->render(function (AuthenticationException $exception, $request) {
            if (str_contains($request->path(), "api")) {
                return ApiResponse::unauthorized(__("responses.unauthenticated"), []);
            }
        });

        $exceptions->render(function (NotFoundHttpException $exception, $request) {
            if (str_contains($request->path(), "api")) {
                return ApiResponse::unprocessable(__("responses.went_wrong"), []);
            } else {
                return back();
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

        // Retrieve the Stripe prices of open tournaments and checks if the price is the same as in db. If not, it updates it.
        $schedule->command("tournamentPrice:updatePriceFromStripe")->hourly();

        // Delete unverified accounts that are at least 1 month old
        $schedule->command("account:purge-unverified")->monthly();

        // Clear logs files
        $schedule->command("logs:clear")->weekly();

        // Delete tokens expired in personal_access_tokens table
        $schedule->command("sanctum:prune-expired")->daily();

        $schedule->command('clean:directories')->daily();

        $schedule->command("model:prune")->daily();
    })
    ->create();
