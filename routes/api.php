<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\AuthController;
// use App\Http\Controllers\Api\RuleController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TournamentController;
use App\Http\Controllers\Api\PurchasedPlaceController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(AuthController::class)->group(function() {
        Route::get('user', 'user');
        Route::get('email/resend', 'resend')->name('verification.resend.api');
        Route::post('logout', 'logout');
    });
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::controller(AuthController::class)->group(function() {
    Route::post('register', 'register');
    Route::post('login', 'login')->name("login.api");
    Route::get('email/verify/{id}/{hash}', 'verify')->name('verification.verify.api');
    Route::get('not-verified', 'notVerified')->name('verification.notice.api');
    Route::post('forgot-password', 'forgotPassword');
    Route::post('reset-password', 'resetPassword');
});

/*
|--------------------------------------------------------------------------
| Players Routes
|--------------------------------------------------------------------------
*/
Route::controller(UserController::class)->group(function() {
    Route::get('players', 'index');
    Route::get('players/paginate/{item_per_page}', 'index_paginate');
    Route::get('players/{player}', 'show');
    Route::get('players/{player}/tournaments', 'playerTournaments');
    Route::get('players/{player}/teams', 'playerTeams');
});

/*
|--------------------------------------------------------------------------
| Games Routes
|--------------------------------------------------------------------------
*/
Route::controller(GameController::class)->group(function() {
    Route::get('games', 'index')->name('games.index.api');
    Route::get('games/paginate/{item_per_page}', 'index_paginate');
    Route::get('games/{game}', 'show')->name('games.show.api');
});

/*
|--------------------------------------------------------------------------
| Teams Routes
|--------------------------------------------------------------------------
*/
Route::controller(TeamController::class)->group(function() {
    Route::get('teams', 'index');
    Route::get('teams/paginate/{item_per_page}', 'index_paginate');
    Route::get('teams/{team}', 'show');
});

/*
|--------------------------------------------------------------------------
| Tournaments Routes
|--------------------------------------------------------------------------
*/
Route::controller(TournamentController::class)->group(function() {
    Route::get('tournaments', 'index');
    Route::get('tournaments/prices', 'prices');
    Route::get('tournaments/paginate/{item_per_page}', 'index_paginate');
    Route::get('tournaments/{tournament}', 'show')->name('tournaments.show.api');
    Route::get('tournaments/{tournament}/purchasedPlaces', 'showPurchasedPlaces');
});

/*
|--------------------------------------------------------------------------
| Purchased Places Routes
|--------------------------------------------------------------------------
*/
Route::controller(PurchasedPlaceController::class)->group(function() {
    Route::get('purchasedPlaces', 'index');
    Route::get('purchasedPlaces/{purchasedPlace}', 'show');
});

// Route::controller(RuleController::class)->group(function () {
//     Route::get('/rules', 'showApi');
// });

// Route::controller(FaqController::class)->group(function () {
//     Route::get('/faq', 'index');
// });

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    /*
    |--------------------------------------------------------------------------My Workspace
    
    | Auth Players Routes
    |--------------------------------------------------------------------------
    */
    Route::controller(UserController::class)->group(function() {
        Route::put('players/{player}', 'update');
        Route::delete('players/{player}', 'delete');
        Route::post('players/{player}/leaveTeam/{team}', 'leaveTeam');     
    });
        
    /*
    |--------------------------------------------------------------------------
    | Auth Teams Routes
    |--------------------------------------------------------------------------
    */
    Route::controller(TeamController::class)->group(function() {
        Route::post('teams/create', 'create');
        Route::post('teams/{team}/addPlayer/{player}', 'addPlayer');
        Route::post('teams/{team}/removePlayer/{player}', 'removePlayer');
        Route::put('teams/{team}', 'update');
        Route::delete('teams/{team}', 'delete');
    });
    
    /*
    |--------------------------------------------------------------------------
    | Auth Tournaments Routes
    |--------------------------------------------------------------------------
    */
    Route::controller(TournamentController::class)->group(function() {
        Route::post('tournaments', 'create');
        Route::post('tournaments/{tournament}/getPaymentLink', 'getPaymentLink');
        Route::post('tournaments/{tournament}/register/{player}', 'register');
        Route::post('tournaments/{tournament}/unregister/{player}', 'unregister');
        Route::put('tournaments/{tournament}', 'update');
        Route::delete('tournaments/{tournament}', 'delete');
    });

    /*
    |--------------------------------------------------------------------------
    | Auth Purchased Places Routes
    |--------------------------------------------------------------------------
    */
    Route::controller(PurchasedPlaceController::class)->group(function() {
        Route::post('purchasedPlaces/register/{user}/{tournament}', 'registerPurchase');
    });
});
