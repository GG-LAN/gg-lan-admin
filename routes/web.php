<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\ProfileController;



Route::middleware(['auth', 'verified'])->group(function () {
    Route::get("/", function() {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::apiResource('players', PlayerController::class);
    Route::controller(PlayerController::class)->group(function () {
        Route::get('/api/players/{player}', 'showApi')->name('players.show.api');
    });

    Route::apiResource('games', GameController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
