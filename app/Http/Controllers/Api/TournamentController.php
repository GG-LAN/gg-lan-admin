<?php
namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Requests\Tournaments\GetPaymentLinkRequest;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\TournamentPrice;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TournamentController extends Controller
{
    /**
     * Get all tournaments
     * 
     * @unauthenticated
     */
    public function index(): JsonResponse
    {
        return ApiResponse::success("", Tournament::without('players', 'teams')->get());
    }

    /**
     * Get a paginate version of all the tournaments
     * 
     * @unauthenticated
     */
    public function index_paginate($item_per_page): JsonResponse
    {
        return ApiResponse::success("", Tournament::without('players', 'teams')->paginate($item_per_page));
    }

    /**
     * Get a tournament
     * 
     * @unauthenticated
     */
    public function show(Tournament $tournament): JsonResponse
    {
        return ApiResponse::success("", $tournament);
    }

    /**
     * Get purchased places of a tournament
     *
     * @unauthenticated
     */
    public function showPurchasedPlaces(Tournament $tournament): JsonResponse
    {
        if (! $tournament) {
            return ApiResponse::unprocessable(__("responses.tournament.not_exists"), []);
        }

        return ApiResponse::success("", $tournament->purchasedPlaces);
    }

    /**
     * Register a player in the tournament
     */
    public function register(Tournament $tournament, User $player): JsonResponse
    {
        // If the tournament is a team tournament
        if ($tournament->type == "team") {
            return ApiResponse::forbidden(__('responses.tournament.not_solo'), []);
        }

        // If the tournament is full
        if ($tournament->isFull) {
            return ApiResponse::forbidden(__("responses.tournament.full"), []);
        }

        // If the auth user is not the same user in request AND it's not an auth admin
        if (Auth::user()->id != $player->id && ! Auth::user()->admin) {
            return ApiResponse::forbidden(__("responses.tournament.player_not_you"), []);
        }

        if (! $tournament->checkPlayerIsRegistered($player)) {
            $tournament->players()->attach($player);

            return ApiResponse::success(__("responses.tournament.registered"), $tournament);
        }

        return ApiResponse::forbidden(__("responses.tournament.already_registered"), []);
    }

    /**
     * Unregister a player in the tournament
     */
    public function unregister(Tournament $tournament, User $player): JsonResponse
    {
        if (Auth::user()->id != $player->id && ! Auth::user()->admin) {
            return ApiResponse::forbidden(__("responses.tournament.player_not_you"), []);
        }

        if ($tournament->checkPlayerIsRegistered($player)) {
            $tournament->players()->detach($player);

            return ApiResponse::success(__("responses.tournament.unregistered"), $tournament);
        }

        return ApiResponse::forbidden(__("responses.tournament.not_registered"), []);
    }

    /**
     * Get all the price for each open tournaments
     * 
     * @unauthenticated
     */
    public function prices(): JsonResponse
    {
        $tournaments = Tournament::where("status", "open")->get();
        $prices      = [];

        foreach ($tournaments as $key => $tournament) {
            $currentPrice = $tournament->currentPrice();

            if (! $currentPrice) {
                continue;
            }

            $stripePrice                          = TournamentPrice::getStripePrice($currentPrice->price_id);
            $stripePrice["tournament_name"]       = $tournament->name;
            $stripePrice["tournament_price_type"] = $currentPrice->type;

            array_push($prices, $stripePrice);
        }

        return ApiResponse::success("", $prices);
    }

    /**
     * Get a payment link for a tournament
     */
    public function getPaymentLink(GetPaymentLinkRequest $request, Tournament $tournament): JsonResponse
    {
        return ApiResponse::success("", [
            "payment_url" => $tournament->getPaymentLink($request),
        ]);
    }

    /**
     * Get all the available teams for a tournament
     *
     * @unauthenticated
     */
    public function availableTeams(Tournament $tournament): JsonResponse
    {
        if ($tournament->status != "open") {
            return ApiResponse::forbidden(__('responses.tournament.not_exists'), []);
        }

        if ($tournament->type == "solo") {
            return ApiResponse::forbidden(__('responses.tournament.not_team_tournament'), []);
        }

        $teams = $tournament
            ->teams()
            ->where("registration_state", Team::NOT_FULL)
            ->get();

        return ApiResponse::success("", $teams);
    }
}
