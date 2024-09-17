<?php

namespace App\Http\Controllers;

use App\Http\Requests\Teams\UpdateTeamRequest;
use App\Models\Team;
use App\Tables\TeamPlayers;
use App\Tables\Teams;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = [
            [
                "label" => "Équipes",
                "route" => route('teams.index'),
                "active" => true,
            ],
        ];

        return Inertia::render('Teams/Index', [
            "table" => Teams::table($request),
            "breadcrumbs" => $breadcrumbs,
        ]);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Team $team)
    {
        $breadcrumbs = [
            [
                "label" => "Équipes",
                "route" => route('teams.index'),
                "active" => false,
            ],
            [
                "label" => $team->name,
                "active" => true,
            ],
        ];

        return Inertia::render('Teams/Show', [
            "team" => $team,
            "teamPlayers" => fn() => TeamPlayers::table(team: $team),
            "breadcrumbs" => $breadcrumbs,
        ]);
    }

    public function update(UpdateTeamRequest $request, Team $team)
    {
        $team->update([
            "name" => $request->name,
            "description" => $request->description,
        ]);

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.team.updated'));

        return back();
    }

    public function destroy(string $id)
    {
        //
    }
}
