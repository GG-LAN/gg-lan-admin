<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Inertia\Inertia;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request) {
        $teams = Team::getTeams(5, $request->search);

        $rowsInfo = [
            "rows" => [
                "name" => [
                    "type" => "text",
                    "title" => "Nom",
                ],
                "description" => [
                    "type" => "text",
                    "title" => "Description",
                ],
                "created_at" => [
                    "type" => "text",
                    "title" => "Créée le"
                ],
                "registration_state" => [
                    "type" => "status",
                    "title" => "Statut",
                    "status" => [
                        [
                            "id" => "not_full",
                            "text" => "Incomplète",
                            "color" => "red"
                        ],
                        [
                            "id" => "pending",
                            "text" => "En attente",
                            "color" => "orange"
                        ],
                        [
                            "id" => "registered",
                            "text" => "Inscrite",
                            "color" => "green"
                        ],
                    ]
                ],
                    
            ],
            "actions" => [
                "search" => true,
                // "create" => true,
                // "update" => true,
                // "delete" => true,
                // "show" => [
                //     "route" => "tournaments.show"
                // ]
            ],
        ];

        $breadcrumbs = [
            [
                "label"   => "Équipes",
                "route"   => route('teams.index'),
                "active"  => true
            ]
        ];

        return Inertia::render('Teams/Index', [
            "tableData"     => $teams,
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
