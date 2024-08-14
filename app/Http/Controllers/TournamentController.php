<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Inertia\Inertia;
use App\Models\Setting;
use App\Models\Tournament;
use App\Tables\Tournaments;
use App\Helpers\ImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use App\Models\TournamentPrice;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Tournaments\StoreTournamentRequest;
use App\Http\Requests\Tournaments\UpdateTournamentRequest;
use App\Http\Requests\Tournaments\UpdateTournamentImageRequest;

class TournamentController extends Controller
{
    public function index(Request $request) {
        $breadcrumbs = [
            [
                "label"   => "Tournois",
                "route"   => route('tournaments.index'),
                "active"  => true
            ]
        ];

        return Inertia::render('Tournaments/Index', [
            "table" => Tournaments::table($request),
            "breadcrumbs" => $breadcrumbs,
        ]);
    }

    public function store(StoreTournamentRequest $request) {
        $path = null;
            
        if ($request->hasFile("image")) {
            $file = $request->file('image');

            $path = $file->store('public/tournament-image');
            $path = str_replace("public", "/storage", $path);
        }

        $game = Game::find($request->game_id);

        $tournament = Tournament::create([
            "name"        => $request->name,
            "description" => $request->description,
            "game_id"     => $request->game_id,
            "start_date"  => $request->start_date,
            "end_date"    => $request->end_date,
            "places"      => $request->places,
            "cashprize"   => $request->cashprize,
            "status"      => "closed",
            "image"       => $path,
            "type"        => $game->places > 1 ? "team" : "solo",
        ]);

        // Create Stripe Product
        $product = TournamentPrice::createProduct([
            "name" => $tournament->name
        ]);

        $normalPrice = $request->normal_place_price;
        $lastWeekPrice = $request->last_week_place_price;
        
        if (!$lastWeekPrice) {
            $lastWeekPrice = $normalPrice;
        }

        // Normal price
        TournamentPrice::create([
            "name"          => $tournament->name,
            "tournament_id" => $tournament->id,
            "type"          => "normal",
            "currency"      => "eur",
            "unit_amount"   => $normalPrice,
            "product"       => $product->id,
            "active"        => true
        ]);

        // Last week price
        TournamentPrice::create([
            "name"          => $tournament->name . " Last Week",
            "tournament_id" => $tournament->id,
            "type"          => "last_week",
            "currency"      => "eur",
            "unit_amount"   => $lastWeekPrice,
            "product"       => $product->id
        ]);

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.tournament.created'));

        return to_route('tournaments.show', ['tournament' => $tournament->id]);
        // return back();
    }

    public function show(Tournament $tournament) {
        $breadcrumbs = [
            [
                "label"   => "Tournois",
                "route"   => route('tournaments.index'),
                "active"  => false
            ],
            [
                "label"   => $tournament->name,
                "active"  => true
            ]
        ];

        $pricesData = $tournament->prices()->paginate(5)->through(function($tournamentPrice) {
            return [
                "id" => $tournamentPrice->id,
                "name" => $tournamentPrice->name,
                "active" => $tournamentPrice->active,
                "price" => $tournamentPrice->price,
            ];
        });
        
        $pricesRowsInfo = [
            "rows" => [
                "name" => [
                    "type" => "text",
                    "label" => "Nom",
                ],
                "price" => [
                    "type" => "text",
                    "label" => "Prix",
                ],
                "active" => [
                    "type" => "bool",
                    "label" => "Statut",
                    "label_true" => "Actif",
                    "label_false" => "Inactif",
                ],
            ],
            "actions" => [
                // "search" => true,
                // "create" => true,
                // "update" => true,
                // "delete" => true,
                // "show" => [
                //     "route" => "teams.show"
                // ]
            ],
        ];

        $teamsData = [];
        $playersData = [];
        $teamsRowsInfo = [];
        $playersRowsInfo = [];

        if ($tournament->type == "team") {
            $teamsData = $tournament->teams()->paginate(5)->through(function($team) {
                return [
                    "id" => $team->id,
                    "name" => $team->name,
                    "registration_state" => $team->registration_state,
                ];
            });
    
            $teamsRowsInfo = [
                "rows" => [
                    "name" => [
                        "name" => "name",
                        "type" => "text",
                        "label" => "Nom",
                    ],
                    "registration_state" => [
                        "name" => "registration_state",
                        "type" => "badge",
                        "label" => "Statut",
                        "badges" => [
                            [
                                "value" => "not_full",
                                "label" => "Incomplète",
                                "color" => "red"
                            ],
                            [
                                "value" => "pending",
                                "label" => "En attente",
                                "color" => "orange"
                            ],
                            [
                                "value" => "registered",
                                "label" => "Inscrite",
                                "color" => "green"
                            ],
                        ]
                    ],
                ],
                "actions" => [],
            ];
        }
        else {
            $playersData = $tournament->players()->paginate(5)->through(function($player) {
                return [
                    "id" => $player->id,
                    "pseudo" => $player->pseudo,
                ];
            });
    
            $playersRowsInfo = [
                "rows" => [
                    "pseudo" => [
                        "name" => "pseudo",
                        "type" => "text",
                        "label" => "Pseudo",
                    ],
                ],
                "actions" => [
                    "show" => [
                        "route" => "players.show"
                    ]
                ],
            ];
        }
        
        return Inertia::render('Tournaments/Show', [
            "pricesData" => $pricesData,
            "pricesRowsInfo" => $pricesRowsInfo,

            "teamsData" => $teamsData,
            "teamsRowsInfo" => $teamsRowsInfo,
            
            "playersData" => $playersData,
            "playersRowsInfo" => $playersRowsInfo,
            
            "games" => Game::all(),
            "breadcrumbs" => $breadcrumbs,
            "tournament"  => $tournament
        ]);
    }

    public function update(UpdateTournamentRequest $request, Tournament $tournament) {
        $game = Game::find($request->game_id);

        $tournament->update([
            "name"        => $request->name,
            "description" => $request->description,
            "game_id"     => $request->game_id,
            "start_date"  => $request->start_date,
            "end_date"    => $request->end_date,
            "places"      => $request->places,
            "cashprize"   => $request->cashprize,
            "type"        => $game->places > 1 ? "team" : "solo",
        ]);

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.tournament.updated'));

        return back();
    }

    public function destroy(Request $request, Tournament $tournament) {
        if ($tournament->image != "") {
            $path = str_replace("/storage", "public", $tournament->image);
            Storage::delete($path);
        }
        
        $tournament->delete();

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.tournament.deleted'));
        
        return to_route('tournaments.index');
    }

    public function showApi(Tournament $tournament) {
        return $tournament;
    }

    public function openTournament(Request $request, Tournament $tournament) {
        $tournament->status = "open";
        $tournament->save();

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.tournament.opened'));

        return back();
    }

    public function updateImage(UpdateTournamentImageRequest $request, Tournament $tournament) {
        if ($request->hasFile("image")) {
            $file = $request->file('image');
            
            $path = ImageUpload::storeOrUpdate($file, $tournament->image, "storage/tournament-image");

            $tournament->image = $path;
            $tournament->save();
        }

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.tournament.updated'));

        return back();
    }
    
    public function deleteImage(Request $request, Tournament $tournament) {
        if ($tournament->image == "" || $tournament->image === null) {
            $request->session()->flash('status', 'error');
            $request->session()->flash('message', __('responses.errors.something_went_wrong'));

            return back();
        }

        $path = str_replace("/storage", "public", $tournament->image);
        Storage::delete($path);

        $tournament->update([
            'image' => null
        ]);

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.tournament.updated'));

        return back();
    }
}
