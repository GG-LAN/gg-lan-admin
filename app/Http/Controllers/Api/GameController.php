<?php
namespace App\Http\Controllers\Api;

use Auth;
use App\Models\Game;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Games\StoreGameRequest;
use App\Http\Requests\Games\UpdateGameRequest;

class GameController extends Controller
{
    public function __construct() {

    }
    
    /**
     * Return all the games
     *
     * @return array
     */
    public function index() {
        return ApiResponse::success("", Game::all());
    }

    /**
     * Return a paginate version of all the games
     *
     * @param int $item_per_page
     * @return array
     */
    public function index_paginate($item_per_page) {
        return ApiResponse::success("", Game::paginate($item_per_page));
    }

    /**
     * Return a player
     *
     * @param int $id
     * @return Game
     */
    public function show(Game $game) {
        return ApiResponse::success("", $game);
    }
}