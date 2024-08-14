<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Inertia\Inertia;
use App\Tables\Teams;
use Illuminate\Http\Request;
use App\Http\Requests\Teams\UpdateTeamRequest;

class TeamController extends Controller
{
    public function index(Request $request) {
        $breadcrumbs = [
            [
                "label"   => "Équipes",
                "route"   => route('teams.index'),
                "active"  => true
            ]
        ];

        return Inertia::render('Teams/Index', [
            "table"       => Teams::table($request),
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
                    "label" => "Pseudo",
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
