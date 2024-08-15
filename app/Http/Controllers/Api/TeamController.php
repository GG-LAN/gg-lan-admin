<?php
namespace App\Http\Controllers\Api;

use App\ApiCode;
use App\Models\Team;
use App\Models\User;
use App\Models\Tournament;
use App\Events\TeamUpdated;
use App\Helpers\ApiResponse;
use App\Models\PurchasedPlace;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Teams\StoreTeamRequest;
use App\Http\Requests\Teams\UpdateApiTeamRequest;

class TeamController extends Controller {

    public function __construct() {

    }

    /**
     * Return all the teams
     */
    public function index() {
        return ApiResponse::success("", Team::without('users')->withCount('users')->get());
    }

    /**
     * Return a paginate version of all the teams
     */
    public function index_paginate($item_per_page) {
        return ApiResponse::success("", Team::without('users')->withCount('users')->paginate($item_per_page));
    }

    /**
     * Return a team
     */
    public function show(Team $team) {
        return ApiResponse::success("", $team);
    }

    /**
     * Create a team
     */
    public function create(StoreTeamRequest $request): JsonResponse {
        $tournament = Tournament::findOrFail($request->tournament_id);

        if ($tournament->type != "team") {
            return ApiResponse::forbidden(__("responses.teams.register.cant_solo"), []);
        }

        if ($tournament->status != "open") {
            return ApiResponse::forbidden(__("responses.teams.register.cant_closed"), []);
        }
        
        // Create the team
        $team = Team::create([
            'name'          => $request->name,
            'description'   => $request->description,
            'image'         => $request->image,
            'tournament_id' => $request->tournament_id
        ]);
        $captain = Auth::user();

        // Add the current player to the team as captain
        $team->users()->attach($captain, ['captain' => true]);
        PurchasedPlace::register($captain, $tournament);
        
        return ApiResponse::created("", $team);
    }

    /**
     * Update a team
     */
    public function update(UpdateApiTeamRequest $request, Team $team): JsonResponse {
        $team->update([
            "name"        => $request->name,
            "description" => $request->description,
            "image"       => $request->image,
        ]);

        return ApiResponse::success("", $team);
    }

    /**
     * Add a player to the team
     */
    public function addPlayer(Team $team, User $player): JsonResponse {
        // If the team is "not_full"
        if ($team->registration_state == Team::NOT_FULL) {
            $countPlayers = $team->users()->count();
            
            // If the player is already in the team
            if ($team->users->where('id', $player->id)->first()) {
                return ApiResponse::forbidden(__("responses.teams.player_in_team"), []);
            }
            
            $team->users()->attach($player);
            PurchasedPlace::register($player, $team->tournament);

            TeamUpdated::dispatch($team);

            return ApiResponse::success(__("responses.teams.player_added"), $team);
        }
        else {
            return ApiResponse::forbidden(__("responses.teams.full"), []);
        }
    }

    /**
     * Remove a player of the team
     */
    public function removePlayer(Team $team, User $player): JsonResponse {
        // If the auth user is not the captain of this team
        if ($team->captain_id != Auth::user()->id) {
            return ApiResponse::forbidden(__("responses.teams.not_captain"), []);
        }
        
        // If the player is not in the team
        if (!$team->users->where('id', $player->id)->first()) {
            return ApiResponse::forbidden(__("responses.teams.player_not_in_team"), []);
        }
        
        // If the player is the captain of the team
        if ($team->captain_id == $player->id) {
            return ApiResponse::forbidden(__("responses.teams.cant_remove_captain"), []);
        }

        $team->users()->detach($player);
        PurchasedPlace::unregister($player, $team->tournament);

        TeamUpdated::dispatch($team);

        return ApiResponse::success(__("responses.teams.player_removed"), $team);
    }

    /**
     * Delete the team
     */
    public function delete(Team $team): JsonResponse {
        if ($team->captain_id != Auth::user()->id) {
            return ApiResponse::forbidden(__("responses.teams.not_captain"), []);
        }
        
        $team->delete();
        
        return ApiResponse::success(__("responses.teams.deleted"), []);
    }
}