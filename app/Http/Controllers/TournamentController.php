<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Inertia\Inertia;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use App\Models\TournamentPrice;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Tournaments\StoreTournamentRequest;
use App\Http\Requests\Tournaments\UpdateTournamentRequest;

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
                "update" => true,
                "delete" => true,
                "show" => [
                    "route" => "tournaments.show"
                ]
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

    public function store(StoreTournamentRequest $request) {
        $path = null;
            
        if ($request->hasFile("image")) {
            $file = $request->file('image');

            $path = $file->store('public/tournament-image');
        }

        $tournament = Tournament::create([
            "name"        => $request->name,
            "description" => $request->description,
            "game_id"     => $request->game_id,
            "start_date"  => $request->start_date,
            "end_date"    => $request->end_date,
            "places"      => $request->places,
            "cashprize"   => $request->cashprize,
            "status"      => $request->status,
            "image"       => $path,
            "type"        => $request->type,
        ]);

        // Create Stripe Product
        $product = TournamentPrice::createProduct([
            "name" => $tournament->name
        ]);

        // Multiply by 100 because Stripe take amount in cents
        $normalPrice = $request->normal_place_price * 100;
        
        $lastWeekPrice = $request->last_week_place_price;
        if (!$lastWeekPrice) {
            $lastWeekPrice = $normalPrice;
        }
        else {
            // Multiply by 100 because Stripe take amount in cents
            $lastWeekPrice = $lastWeekPrice * 100;
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
        // $request->session()->flash('message', __('responses.tournament.created'));

        return back();
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
                "price" => Number::currency($tournamentPrice->stripe_price->unit_amount / 100, 'EUR', 'FR'),
            ];
        });

        $pricesRowsInfo = [
            "rows" => [
                "name" => [
                    "type" => "text",
                    "title" => "Nom",
                ],
                "price" => [
                    "type" => "text",
                    "title" => "Prix",
                ],
                "active" => [
                    "type" => "bool",
                    "title" => "Statut",
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
        
        // $soloTournamentsData = $player->tournaments()->paginate(5);

        // $soloTournamentsRowsInfo = [
        //     "rows" => [
        //         "name" => [
        //             "type" => "text",
        //             "title" => "Nom",
        //         ]
        //     ],
        //     "actions" => [
        //         // "search" => true,
        //         // "create" => true,
        //         // "update" => true,
        //         // "delete" => true,
        //         "show" => [
        //             "route" => "tournaments.show"
        //         ]
        //     ],
        // ];

        return Inertia::render('Tournaments/Show', [
            "pricesData" => $pricesData,
            "pricesRowsInfo" => $pricesRowsInfo,
            // "soloTournamentsData" => $soloTournamentsData,
            // "soloTournamentsRowsInfo" => $soloTournamentsRowsInfo,
            "games" => Game::all(),
            "status" => [
                ["id" => "open", "name" => "Ouvert"],
                ["id" => "closed", "name" => "Fermé"],
                ["id" => "finished", "name" => "Terminé"],
            ],
            "types" => [
                ["id" => "team", "name" => "Équipe"],
                ["id" => "solo", "name" => "Solo"],
            ],
            "breadcrumbs" => $breadcrumbs,
            "tournament"  => $tournament
        ]);
    }

    public function update(UpdateTournamentRequest $request, Tournament $tournament) {
        // return dd($request->name);
        // if ($request->hasFile("image")) {
        //     $file = $request->file('image');

        //     if ($tournament->image != "") {
        //         Storage::delete($tournament->image);
        //     }

        //     $path = $file->store('public/tournament-image');
        //     $name = $file->getClientOriginalName();

        //     $tournament->image = $path;
        // }

        $tournament->update([
            "name"        => $request->name,
            "description" => $request->description,
            "game_id"     => $request->game_id,
            "start_date"  => $request->start_date,
            "end_date"    => $request->end_date,
            "places"      => $request->places,
            "cashprize"   => $request->cashprize,
            "status"      => $request->status,
            "type"        => $request->type,
        ]);

        $request->session()->flash('status', 'success');
        // $request->session()->flash('message', __('responses.tournament.updated'));

        return back();
    }

    public function destroy(Request $request, Tournament $tournament) {
        $tournament->delete();

        $request->session()->flash('status', 'success');
        // $request->session()->flash('message', __('responses.tournament.deleted'));
        
        return back();
    }

    public function showApi(Tournament $tournament) {
        return $tournament;
    }
}
