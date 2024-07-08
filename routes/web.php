<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\GameController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TournamentController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get("/", function() {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::apiResource('players', PlayerController::class);
    Route::controller(PlayerController::class)->group(function () {
        Route::get('/api/players/{player}', 'showApi')->name('players.show.api');
    });

    Route::apiResource('games', GameController::class);
    Route::controller(GameController::class)->group(function () {
        Route::get('/api/games', 'indexApi')->name('games.index.api');
        Route::get('/api/games/{game}', 'showApi')->name('games.show.api');
    });

    Route::apiResource('teams', TeamController::class);

    Route::apiResource('tournaments', TournamentController::class);
    Route::controller(TournamentController::class)->group(function () {
        Route::get('/api/tournaments/{tournament}', 'showApi')->name('tournaments.show.api');
        Route::post('/api/tournaments/{tournament}/openTournament', 'openTournament')->name('tournaments.openTournament');
        Route::post('/api/tournaments/{tournament}/updateImage', 'updateImage')->name('tournaments.updateImage');
    });
    
    Route::apiResource('payments', PaymentController::class);
    Route::controller(PaymentController::class)->group(function () {
    });

    Route::apiResource('teams', TeamController::class);
    Route::controller(TeamController::class)->group(function () {
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
