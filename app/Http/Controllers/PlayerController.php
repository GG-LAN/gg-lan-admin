<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index(Request $request) {
        $players = User::getPlayers(5, $request->search);
        
        $rowsInfo = [
            "rows" => [
                "name" => [
                    "type" => "text",
                    "title" => "Nom",
                ],
                "pseudo" => [
                    "type" => "text",
                    "title" => "Pseudo",
                ],
                "email" => [
                    "type" => "text",
                    "title" => "Email",
                ],
            ],
            "actions" => [
                "search" => true,
                // "create" => true,
                // "update" => true,
                // "delete" => true,
                // "show" => [
                //     "route" => "servers.show"
                // ]
            ],
        ];

        $breadcrumbs = [
            [
                "label"   => "Joueurs",
                "route"   => route('players.index'),
                "active"  => true
            ]
        ];

        return Inertia::render('Players/Index', [
            "tableData"     => $players,
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

    public function show(string $id) {
        //
    }

    public function update(Request $request, string $id) {
        //
    }

    public function destroy(string $id) {
        //
    }
}
