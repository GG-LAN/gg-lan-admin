<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Tables\Players;
use Illuminate\Http\Request;
use App\Http\Requests\Players\StorePlayerRequest;
use App\Http\Requests\Players\UpdatePlayerRequest;

class PlayerController extends Controller
{
    public function index(Request $request) {
        $breadcrumbs = [
            [
                "label"   => "Joueurs",
                "route"   => route('players.index'),
                "active"  => true
            ]
        ];

        return Inertia::render('Players/Index', [
            "table"       => Players::table($request),
            "breadcrumbs" => $breadcrumbs,
        ]);
    }

    public function store(StorePlayerRequest $request) {
        User::create([
            "name"       => $request->name,
            "pseudo"     => $request->pseudo,
            "email"      => $request->email,
            "password"   => bcrypt($request->password),
            "birth_date" => $request->birth_date,
            "admin"      => $request->admin,
        ]);

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.player.created'));

        return back();
    }

    public function show(User $player) {
        $breadcrumbs = [
            [
                "label"   => "Joueurs",
                "route"   => route('players.index'),
                "active"  => false
            ],
            [
                "label"   => $player->pseudo,
                "active"  => true
            ]
        ];

        $teamsData = $player->teams()->paginate(5)->through(function ($team) {
            return [
                'id'   => $team->id,
                'name' => $team->name,
                'tournament_name' => $team->tournament->name,
                'is_full' => $team->getIsFullAttribute()
            ];
        });

        $teamsRowsInfo = [
            "rows" => [
                "name" => [
                    "type" => "text",
                    "label" => "Nom",
                ],
                "tournament_name" => [
                    "type" => "text",
                    "label" => "Tournois",
                ],
                "is_full" => [
                    "type" => "bool",
                    "label" => "Status",
                    "label_true" => "Complète",
                    "label_false" => "Incomplète",
                ],
            ],
            "actions" => [
                // "search" => true,
                // "create" => true,
                // "update" => true,
                // "delete" => true,
                "show" => [
                    "route" => "teams.show"
                ]
            ],
        ];
        
        $soloTournamentsData = $player->tournaments()->paginate(5);

        $soloTournamentsRowsInfo = [
            "rows" => [
                "name" => [
                    "type" => "text",
                    "label" => "Nom",
                ]
            ],
            "actions" => [
                // "search" => true,
                // "create" => true,
                // "update" => true,
                // "delete" => true,
                "show" => [
                    "route" => "tournaments.show"
                ]
            ],
        ];

        return Inertia::render('Players/Show', [
            "teamsData" => $teamsData,
            "teamsRowsInfo" => $teamsRowsInfo,
            "soloTournamentsData" => $soloTournamentsData,
            "soloTournamentsRowsInfo" => $soloTournamentsRowsInfo,
            "breadcrumbs" => $breadcrumbs,
            "player"      => $player
        ]);
    }

    public function update(UpdatePlayerRequest $request, User $player) {
        $player->update([
            "pseudo" => $request->pseudo,
            "admin"  => $request->admin
        ]);


        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.player.updated'));

        return back();
    }

    public function destroy(Request $request, User $player) {
        $player->delete();

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.player.deleted'));

        return to_route("players.index");
    }

    public function showApi(User $player) {
        return $player;
    }
}
