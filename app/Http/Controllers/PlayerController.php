<?php

namespace App\Http\Controllers;

use App\Http\Requests\Players\StorePlayerRequest;
use App\Http\Requests\Players\UpdatePlayerRequest;
use App\Models\User;
use App\Tables\Players;
use App\Tables\PlayerTeams;
use App\Tables\PlayerTournaments;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlayerController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = [
            [
                "label" => "Joueurs",
                "route" => route('players.index'),
                "active" => true,
            ],
        ];

        return Inertia::render('Players/Index', [
            "table" => Players::table($request),
            "breadcrumbs" => $breadcrumbs,
        ]);
    }

    public function store(StorePlayerRequest $request)
    {
        User::create([
            "name" => $request->name,
            "pseudo" => $request->pseudo,
            "email" => $request->email,
            "password" => bcrypt($request->password),
            "birth_date" => $request->birth_date,
            "admin" => $request->admin,
        ]);

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.player.created'));

        return back();
    }

    public function show(User $player)
    {
        $breadcrumbs = [
            [
                "label" => "Joueurs",
                "route" => route('players.index'),
                "active" => false,
            ],
            [
                "label" => $player->pseudo,
                "active" => true,
            ],
        ];

        return Inertia::render('Players/Show', [
            "player" => $player,
            "playerTeams" => fn() => PlayerTeams::table(player: $player),
            "playerTournaments" => fn() => PlayerTournaments::table(player: $player),
            "breadcrumbs" => $breadcrumbs,
        ]);
    }

    public function update(UpdatePlayerRequest $request, User $player)
    {
        $player->update([
            "pseudo" => $request->pseudo,
            "admin" => $request->admin,
        ]);

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.player.updated'));

        return back();
    }

    public function destroy(Request $request, User $player)
    {
        $player->delete();

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.player.deleted'));

        return to_route("players.index");
    }

    public function showApi(User $player)
    {
        return $player;
    }
}
