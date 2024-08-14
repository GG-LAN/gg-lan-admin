<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Inertia\Inertia;
use App\Tables\Games;
use Illuminate\Http\Request;
use App\Http\Requests\Games\StoreGameRequest;
use App\Http\Requests\Games\UpdateGameRequest;

class GameController extends Controller
{

    public function index(Request $request) {
        $breadcrumbs = [
            [
                "label"   => "Jeux",
                "route"   => route('games.index'),
                "active"  => true
            ]
        ];

        return Inertia::render('Games/Index', [
            "table" => Games::table($request),
            "breadcrumbs" => $breadcrumbs,
        ]);
    }

    public function store(StoreGameRequest $request) {
        Game::create([
            "name" => $request->name,
            "places" => $request->places
        ]);

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.game.created'));

        return back();
    }

    public function update(UpdateGameRequest $request, Game $game) {
        $game->update([
            "name" => $request->name,
            "places" => $request->places
        ]);

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.game.updated'));

        return back();
    }

    public function destroy(Request $request, Game $game) {
        $game->delete();

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.game.deleted'));

        return back();
    }

    public function indexApi() {
        return Game::all();
    }

    public function showApi(Game $game) {
        return $game;
    }
}
