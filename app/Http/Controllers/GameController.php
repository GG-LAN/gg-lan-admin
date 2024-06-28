<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Requests\Games\StoreGameRequest;
use App\Http\Requests\Games\UpdateGameRequest;

class GameController extends Controller
{

    public function index(Request $request) {
        $games = Game::getGames(5, $request->search);

        $rowsInfo = [
            "rows" => [
                "name" => [
                    "type" => "text",
                    "title" => "Nom",
                ],
                "places" => [
                    "type" => "text",
                    "title" => "Joueurs / Equipe",
                ],
                "game_type" => [
                    "type" => "text",
                    "title" => "Type",
                ],
            ],
            "actions" => [
                "search" => true,
                "create" => true,
                "update" => true,
                "delete" => true,
            ],
        ];

        $breadcrumbs = [
            [
                "label"   => "Jeux",
                "route"   => route('games.index'),
                "active"  => true
            ]
        ];

        return Inertia::render('Games/Index', [
            "tableData"     => $games,
            "tableRowsInfo" => $rowsInfo,
            "filters" => [
                "search" => $request->search
            ],
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
