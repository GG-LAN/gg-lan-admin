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
     * Return all tournaments
     */
    public function index()
    {
        return ApiResponse::success("", Tournament::without('players', 'teams')->get());
    }

    /**
     * Return a paginate version of all the tournaments
     */
    public function index_paginate($item_per_page)
    {
        return ApiResponse::success("", Tournament::without('players', 'teams')->paginate($item_per_page));
    }

    /**
     * Return a tournament
     */
    public function show(Tournament $tournament): JsonResponse
    {
        return ApiResponse::success("", $tournament);
    }

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
     * Return all the price for each open tournaments
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

    public function getPaymentLink(GetPaymentLinkRequest $request, Tournament $tournament)
    {
        return ApiResponse::success("", [
            "payment_url" => $tournament->getPaymentLink($request),
        ]);
    }

    public function availableTeams(Tournament $tournament)
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
