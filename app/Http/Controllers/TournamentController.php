<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function index(Request $request) {
        $tournaments = Tournament::getTournaments(5, $request->search);

        $rowsInfo = [
            "rows" => [
                "name" => [
                    "type" => "text",
                    "title" => "Nom",
                ],
                "game" => [
                    "type" => "text",
                    "title" => "Jeu",
                ],
                "date" => [
                    "type" => "text",
                    "title" => "Dates Début | Fin",
                ],
                "type" => [
                    "type" => "text",
                    "title" => "Type",
                ],
                "places" => [
                    "type" => "text",
                    "title" => "Places",
                ],
                "cashprize" => [
                    "type" => "text",
                    "title" => "Cashprize (€)",
                ],
                "status" => [
                    "type" => "bool",
                    "title" => "Statut",
                    "label_true" => "Ouvert",
                    "label_false" => "Fermé/Terminé",
                ],
            ],
            "actions" => [
                "search" => true,
                "create" => true,
                // "update" => true,
                // "delete" => true,
                // "show" => [
                //     "route" => "tournaments.show"
                // ]
            ],
        ];

        $breadcrumbs = [
            [
                "label"   => "Tournois",
                "route"   => route('tournaments.index'),
                "active"  => true
            ]
        ];

        return Inertia::render('Tournaments/Index', [
            "tableData"     => $tournaments,
            "tableRowsInfo" => $rowsInfo,
            "filters" => [
                "search" => $request->search
            ],
            "breadcrumbs" => $breadcrumbs,
        ]);
    }

    public function store(Request $request) {
        Tournament::create([
            "name"        => $request->name,
            "description" => $request->description,
            "game_id"     => $request->game_id,
            "start_date"  => $request->start_date,
            "end_date"    => $request->end_date,
            "places"      => $request->places,
            "cashprize"   => $request->cashprize,
            "status"      => $request->status,
            // "image"       => $request->image,
            "type"        => $request->type,
        ]);

        $request->session()->flash('status', 'success');
        // $request->session()->flash('message', __('responses.tournament.created'));

        return back();
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
