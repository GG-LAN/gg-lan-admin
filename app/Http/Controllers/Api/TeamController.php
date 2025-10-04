<?php
namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Requests\Teams\StoreTeamRequest;
use App\Http\Requests\Teams\UpdateApiTeamRequest;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    /**
     * Get all the teams
     * 
     * @unauthenticated
     */
    public function index(): JsonResponse
    {
        return ApiResponse::success("", Team::without('users')->withCount('users')->get());
    }

    /**
     * Get a paginate version of all the teams
     * 
     * @unauthenticated
     */
    public function index_paginate($item_per_page): JsonResponse
    {
        return ApiResponse::success("", Team::without('users')->withCount('users')->paginate($item_per_page));
    }

    /**
     * Get a team
     * 
     * @unauthenticated
     */
    public function show(Team $team): JsonResponse
    {
        return ApiResponse::success("", $team);
    }

    /**
     * Create a team
     */
    public function create(StoreTeamRequest $request): JsonResponse
    {
        $tournament = Tournament::findOrFail($request->tournament_id);

        if ($tournament->type != "team") {
            return ApiResponse::forbidden(__("responses.team.register.cant_solo"), []);
        }

        if ($tournament->status != "open") {
            return ApiResponse::forbidden(__("responses.team.register.cant_closed"), []);
        }

        // Create the team
        $team = Team::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $request->image,
            'tournament_id' => $request->tournament_id,
        ]);

        return ApiResponse::created("", $team);
    }

    /**
     * Update a team
     */
    public function update(UpdateApiTeamRequest $request, Team $team): JsonResponse
    {
        $team->update([
            "name" => $request->name,
            "description" => $request->description,
            "image" => $request->image,
        ]);

        return ApiResponse::success("", $team);
    }

    /**
     * Add a player to the team
     */
    public function addPlayer(Team $team, User $player): JsonResponse
    {
        // If the team is "not_full"
        if ($team->registration_state == Team::NOT_FULL) {
            // If the player is already in the team
            if ($team->users->where('id', $player->id)->first()) {
                return ApiResponse::forbidden(__("responses.team.player_in_team"), []);
            }

            $team->users()->attach($player);

            return ApiResponse::success(__("responses.team.player_added"), $team);
        } else {
            return ApiResponse::forbidden(__("responses.team.full"), []);
        }
    }

    /**
     * Remove a player of the team
     */
    public function removePlayer(Team $team, User $player): JsonResponse
    {
        // If the auth user is not the captain of this team
        if ($team->captain_id != Auth::user()->id) {
            return ApiResponse::forbidden(__("responses.team.not_captain"), []);
        }

        // If the player is not in the team
        if (!$team->users->where('id', $player->id)->first()) {
            return ApiResponse::forbidden(__("responses.team.player_not_in_team"), []);
        }

        // If the player is the captain of the team
        if ($team->captain_id == $player->id) {
            return ApiResponse::forbidden(__("responses.team.cant_remove_captain"), []);
        }

        $team->users()->detach($player);

        return ApiResponse::success(__("responses.team.player_removed"), $team);
    }

    /**
     * Delete the team
     */
    public function delete(Team $team): JsonResponse
    {
        if ($team->captain_id != Auth::user()->id) {
            return ApiResponse::forbidden(__("responses.team.not_captain"), []);
        }

        $team->delete();

        return ApiResponse::success(__("responses.team.deleted"), []);
    }
}
