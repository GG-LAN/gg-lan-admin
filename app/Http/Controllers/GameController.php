<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Inertia\Inertia;
use Illuminate\Http\Request;

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
                "email" => [
                    "type" => "text",
                    "title" => "Email",
                ],
                "game_type" => [
                    "type" => "text",
                    "title" => "Type",
                ],
            ],
            "actions" => [
                "search" => true,
                // "create" => true,
                // "update" => true,
                // "delete" => true,
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

    public function store(Request $request) {
        //
    }

    public function update(Request $request, Game $game) {
        //
    }

    public function destroy(Game $game) {
        //
    }
}
