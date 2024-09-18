<?php

namespace App\Http\Controllers;

use App\Helpers\ImageUpload;
use App\Http\Requests\Tournaments\StoreTournamentRequest;
use App\Http\Requests\Tournaments\UpdateTournamentImageRequest;
use App\Http\Requests\Tournaments\UpdateTournamentRequest;
use App\Models\Game;
use App\Models\Tournament;
use App\Models\TournamentPrice;
use App\Tables\TournamentPlayers;
use App\Tables\TournamentPrices;
use App\Tables\Tournaments;
use App\Tables\TournamentTeams;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TournamentController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = [
            [
                "label" => "Tournois",
                "route" => route('tournaments.index'),
                "active" => true,
            ],
        ];

        return Inertia::render('Tournaments/Index', [
            "table" => Tournaments::table($request),
            "breadcrumbs" => $breadcrumbs,
        ]);
    }

    public function store(StoreTournamentRequest $request)
    {
        $path = null;

        if ($request->hasFile("image")) {
            $file = $request->file('image');

            $path = $file->store('public/tournament-image');
            $path = str_replace("public", "/storage", $path);
        }

        $game = Game::find($request->game_id);

        $tournament = Tournament::create([
            "name" => $request->name,
            "description" => $request->description,
            "game_id" => $request->game_id,
            "start_date" => $request->start_date,
            "end_date" => $request->end_date,
            "places" => $request->places,
            "cashprize" => $request->cashprize,
            "status" => "closed",
            "image" => $path,
            "type" => $game->places > 1 ? "team" : "solo",
        ]);

        // Create Stripe Product
        $product = TournamentPrice::createProduct([
            "name" => $tournament->name,
        ]);

        $normalPrice = $request->normal_place_price;
        $lastWeekPrice = $request->last_week_place_price;

        if (!$lastWeekPrice) {
            $lastWeekPrice = $normalPrice;
        }

        // Normal price
        TournamentPrice::create([
            "name" => $tournament->name,
            "tournament_id" => $tournament->id,
            "type" => "normal",
            "currency" => "eur",
            "unit_amount" => $normalPrice,
            "product" => $product->id,
            "active" => true,
        ]);

        // Last week price
        TournamentPrice::create([
            "name" => $tournament->name . " Last Week",
            "tournament_id" => $tournament->id,
            "type" => "last_week",
            "currency" => "eur",
            "unit_amount" => $lastWeekPrice,
            "product" => $product->id,
        ]);

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.tournament.created'));

        return to_route('tournaments.show', ['tournament' => $tournament->id]);
        // return back();
    }

    public function show(Tournament $tournament)
    {
        $breadcrumbs = [
            [
                "label" => "Tournois",
                "route" => route('tournaments.index'),
                "active" => false,
            ],
            [
                "label" => $tournament->name,
                "status" => $tournament->status,
                "active" => true,
                "choices" => Tournament::orderByDesc("created_at")->get()->map(function ($mappedTournament) use ($tournament) {
                    if ($mappedTournament->id == $tournament->id) {
                        return;
                    }

                    return [
                        "label" => $mappedTournament->name,
                        "status" => $mappedTournament->status,
                        "route" => route("tournaments.show", $mappedTournament->id),
                    ];
                }),

            ],
        ];

        $tournamentTeams = [];
        $tournamentPlayers = [];

        if ($tournament->type == "team") {
            $tournamentTeams = TournamentTeams::table(tournament: $tournament);
        } else {
            $tournamentPlayers = TournamentPlayers::table(tournament: $tournament);
        }

        return Inertia::render('Tournaments/Show', [
            "tournament" => $tournament,
            "tournamentPrices" => fn() => TournamentPrices::table(tournament: $tournament),
            "tournamentTeams" => fn() => TournamentTeams::table(tournament: $tournament),
            "tournamentPlayers" => fn() => TournamentPlayers::table(tournament: $tournament),
            "games" => Game::all(),
            "breadcrumbs" => fn() => $breadcrumbs,
        ]);
    }

    public function update(UpdateTournamentRequest $request, Tournament $tournament)
    {
        $game = Game::find($request->game_id);

        $tournament->update([
            "name" => $request->name,
            "description" => $request->description,
            "game_id" => $request->game_id,
            "start_date" => $request->start_date,
            "end_date" => $request->end_date,
            "places" => $request->places,
            "cashprize" => $request->cashprize,
            "type" => $game->places > 1 ? "team" : "solo",
        ]);

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.tournament.updated'));

        return back();
    }

    public function destroy(Request $request, Tournament $tournament)
    {
        if ($tournament->image != "") {
            $path = str_replace("/storage", "public", $tournament->image);
            Storage::delete($path);
        }

        $tournament->delete();

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.tournament.deleted'));

        return to_route('tournaments.index');
    }

    public function showApi(Tournament $tournament)
    {
        return $tournament;
    }

    public function openTournament(Request $request, Tournament $tournament)
    {
        $tournament->status = "open";
        $tournament->save();

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.tournament.opened'));

        return back();
    }

    public function updateImage(UpdateTournamentImageRequest $request, Tournament $tournament)
    {
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

    public function deleteImage(Request $request, Tournament $tournament)
    {
        if ($tournament->image == "" || $tournament->image === null) {
            $request->session()->flash('status', 'error');
            $request->session()->flash('message', __('responses.errors.something_went_wrong'));

            return back();
        }

        $path = str_replace("/storage", "public", $tournament->image);
        Storage::delete($path);

        $tournament->update([
            'image' => null,
        ]);

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.tournament.updated'));

        return back();
    }
}
