<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Requests\Teams\UpdateTeamRequest;

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
                "show" => [
                    "route" => "teams.show"
                ]
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

    public function show(Team $team) {
        $breadcrumbs = [
            [
                "label"   => "Équipes",
                "route"   => route('teams.index'),
                "active"  => false
            ],
            [
                "label"   => $team->name,
                "active"  => true
            ]
        ];

        $playersData = $team->users()->paginate(5)->through(function($player) {
            return [
              "id" => $player->id,
              "pseudo" => $player->pseudo,
            ];
        });

        $playersRowsInfo = [
            "rows" => [
                "pseudo" => [
                    "type" => "text",
                    "title" => "Pseudo",
                ],
            ],
            "actions" => [
                // "search" => true,
                // "create" => true,
                // "update" => true,
                // "delete" => true,
                "show" => [
                    "route" => "players.show"
                ]
            ],
        ];

        return Inertia::render('Teams/Show', [
            "playersData" => $playersData,
            "playersRowsInfo" => $playersRowsInfo,
            
            "breadcrumbs" => $breadcrumbs,
            "team"  => $team
        ]);
    }

    public function update(UpdateTeamRequest $request, Team $team) {
        $team->update([
            "name" => $request->name,
            "description" => $request->description
        ]);

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.team.updated'));

        return back();
    }

    public function destroy(string $id) {
        //
    }
}
