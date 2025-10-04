<?php
namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Models\Game;
use Illuminate\Http\JsonResponse;

class GameController extends Controller
{
    public function __construct()
    {

    }

    /**
     * Get all the games
     *
     * @unauthenticated
     */
    public function index(): JsonResponse
    {
        return ApiResponse::success("", Game::all());
    }

    /**
     * Get a paginate version of all the games
     *
     * @unauthenticated
     */
    public function index_paginate($item_per_page): JsonResponse
    {
        return ApiResponse::success("", Game::paginate($item_per_page));
    }

    /**
     * Get a game
     *
     * @unauthenticated
     */
    public function show(Game $game): JsonResponse
    {
        return ApiResponse::success("", $game);
    }
}
