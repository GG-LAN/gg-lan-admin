<?php
namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Players\LinkFaceitAccountRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Team;
use App\Models\User;
use App\Services\Faceit;
use Auth;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

#[Group('Player')] // Scramble Api Doc Group
class UserController extends Controller
{
    /**
     * Get all the players
     *
     * @unauthenticated
     */
    public function index(): JsonResponse
    {
        return ApiResponse::success("", User::all()->toResourceCollection());
    }

    /**
     * Get a paginate version of all the players
     *
     * @unauthenticated
     */
    public function index_paginate(Request $request, $item_per_page)
    {
        return ApiResponse::success("", User::paginate($item_per_page, ['id', 'pseudo', 'image', 'created_at', 'updated_at']));
    }

    /**
     * Get a player
     *
     * @unauthenticated
     */
    public function show(User $player)
    {
        return ApiResponse::success("", new UserResource($player));
    }

    /**
     * Update a player
     */
    public function update(UpdateUserRequest $request, User $player)
    {
        $player->update([
            "pseudo"     => $request->pseudo,
            "birth_date" => $request->birth_date,
        ]);

        return ApiResponse::success("", new UserResource($player));
    }

    /**
     * Delete a player
     */
    public function delete(User $player): JsonResponse
    {
        if (auth()->user()->admin) {
            $player->delete();

            return ApiResponse::success(__("responses.users.deleted"), []);
        } else {
            return ApiResponse::forbidden(__("responses.users.only_admin"), []);
        }
    }

    /**
     * Get tournaments of a player
     *
     * @unauthenticated
     */
    public function playerTournaments(User $player): JsonResponse
    {
        $tournaments = [];

        $playerTournaments = $player->tournaments;
        foreach ($playerTournaments as $playerTournament) {
            array_push($tournaments, $playerTournament);
        }

        $teams = $player->teams;
        foreach ($teams as $team) {
            array_push($tournaments, $team->tournament);
        }

        return ApiResponse::success("", $tournaments);
    }

    /**
     * Get teams of a player
     *
     * @unauthenticated
     */
    public function playerTeams(User $player): JsonResponse
    {
        return ApiResponse::success("", $player->teams);
    }

    /**
     * Player leave specific team
     */
    public function leaveTeam(User $player, Team $team): JsonResponse
    {
        // If the auth user isn't the same user that tries to leave the team
        if (auth()->user()->id != $player->id) {
            return ApiResponse::forbidden(__("responses.users.player_not_you"), []);
        }

        // If the player isn't in the team
        if (! $team->users->where('id', $player->id)->first()) {
            return ApiResponse::forbidden(__("responses.team.player_not_in_team"), []);
        }

        // If the captain tries to leave the team
        if ($team->captain_id == $player->id) {
            return ApiResponse::forbidden(__("responses.team.captain_cant_leave"), []);
        }

        $team->users()->detach($player);

        return ApiResponse::success(__("responses.users.team_left"), []);
    }

    /**
     * Link Faceit account with nickname
     */
    public function linkFaceitAccount(User $player, LinkFaceitAccountRequest $request): JsonResponse
    {
        $faceitPlayer = Faceit::getPlayerByNickname($request->nickname);

        if (! $faceitPlayer) {
            return ApiResponse::unprocessable(__("responses.faceit.user_not_found"), []);
        }

        $faceitAccount = $player->linkFaceitAccount($faceitPlayer->only([
            "player_id",
            "nickname",
            "steam_id_64",
            "games",
        ]));

        return ApiResponse::success("", $faceitAccount);
    }
}
