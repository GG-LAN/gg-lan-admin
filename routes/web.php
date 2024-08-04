<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\RuleController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TournamentController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get("/", function() {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::apiResource('players', PlayerController::class);
    Route::controller(PlayerController::class)->group(function () {
        Route::get('/admin/players/{player}', 'showApi')->name('players.show.api');
    });

    Route::apiResource('games', GameController::class);

    Route::apiResource('teams', TeamController::class);

    Route::apiResource('tournaments', TournamentController::class);
    Route::controller(TournamentController::class)->group(function () {
        Route::post('/admin/tournaments/{tournament}/openTournament', 'openTournament')->name('tournaments.openTournament');
        Route::post('/admin/tournaments/{tournament}/updateImage', 'updateImage')->name('tournaments.updateImage');
        Route::post('/admin/tournaments/{tournament}/deleteImage', 'deleteImage')->name('tournaments.deleteImage');
    });
    
    Route::apiResource('payments', PaymentController::class);
    Route::controller(PaymentController::class)->group(function () {
    });

    Route::controller(RuleController::class)->group(function () {
        Route::get('/admin/rules', 'showApi')->name('rules.show.api');
        Route::post('/admin/rules', 'update')->name('rules.update');
    });

    Route::controller(SettingController::class)->group(function () {
        Route::get('/settings', 'index')->name('settings.index');
        Route::put('/settings', 'update')->name('settings.update');
        Route::post('/settings/image', 'updateImage')->name('settings.update.image');
    });

    Route::apiResource('faqs', FaqController::class);
    Route::controller(FaqController::class)->group(function () {
        Route::get('/admin/faqs/{faq}', 'showApi')->name('faqs.show.api');
    });

    Route::apiResource('logs', LogController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
