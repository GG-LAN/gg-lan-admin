<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Requests\Players\StorePlayerRequest;
use App\Http\Requests\Players\UpdatePlayerRequest;

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
                "admin" => [
                    "type" => "bool",
                    "title" => "Role",
                    "label_true" => "Admin",
                    "label_false" => "Joueur",
                ],
            ],
            "actions" => [
                "search" => true,
                "create" => true,
                "update" => true,
                "delete" => true,
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

    public function store(StorePlayerRequest $request) {
        User::create([
            "name"       => $request->name,
            "pseudo"     => $request->pseudo,
            "email"      => $request->email,
            "password"   => bcrypt($request->password),
            "birth_date" => $request->birth_date,
            "admin"      => $request->admin,
        ]);

        return back();
    }

    public function show(string $id) {
        //
    }

    public function update(UpdatePlayerRequest $request, User $player) {
        $player->update([
            "name"   => $request->name,
            "pseudo" => $request->pseudo,
            "admin"  => $request->admin
        ]);


        $request->session()->flash('status', 'success');
        // $request->session()->flash('message', __('responses.server.updated'));

        return back();
    }

    public function destroy(Request $request, User $player) {
        $player->delete();

        $request->session()->flash('status', 'success');
        // $request->session()->flash('message', __('responses.player.deleted'));

        return to_route("players.index");
    }

    public function showApi(User $player) {
        return $player;
    }
}
