<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Tournament;
use App\Models\TournamentPrice;
use Illuminate\Database\Seeder;

class TournamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {        
        // Create a game for Tournament
        $gameCsgo = Game::factory()->create([
            "name" => "CS:GO",
            "places" => 5
        ]);

        $gameTrackmania = Game::factory()->create([
            "name" => "Trackmania",
            "places" => 1
        ]);
        
        // Create a open team tournament (id: 1)
        Tournament::factory()
            ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
                return [
                    'name' => $tournament->name,
                    'tournament_id' => $tournament->id,
                    'type' => 'normal',
                    'active' => true
                ];
            }), "prices")
            ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
                return [
                    'name' => $tournament->name . " Last Week",
                    'tournament_id' => $tournament->id,
                    'type' => 'last_week',
                    'price_id' => "price_1OtYO6AOwlBXXotY6zNeBkVJ",
                    'active' => false
                ];
            }), "prices")
            ->for($gameCsgo)
            ->create([
                "id" => 1,
                'name' => 'GG-LAN #18 CS:GO',
                "type" => "team",
                "status" => "open",
                "start_date" => today()->addMonths(2),
                "end_date" => today()->addMonths(2)->addDays(1)
            ]);

        // Create a open solo tournament (id: 2)
        Tournament::factory()
            ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
                return [
                    'name' => $tournament->name,
                    'tournament_id' => $tournament->id,
                    'type' => 'normal',
                    'active' => true
                ];
            }), "prices")
            ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
                return [
                    'name' => $tournament->name . " Last Week",
                    'tournament_id' => $tournament->id,
                    'type' => 'last_week',
                    'price_id' => "price_1OtYO6AOwlBXXotY6zNeBkVJ",
                    'active' => false
                ];
            }), "prices")
            ->for($gameTrackmania)
            ->create([
                "id" => 2,
                'name' => 'GG-LAN #18 Trackmania',
                "type" => "solo",
                "status" => "open",
                "start_date" => today()->addMonths(2),
                "end_date" => today()->addMonths(2)->addDays(1)
            ]);

        // Create a finished team tournament (id: 3)
        Tournament::factory()
        ->for($gameCsgo)
        ->create([
            "id" => 3,
            'name' => 'GG-LAN #17 CS:GO',
            "type" => "team",
            "status" => "finished",
            "start_date" => today()->subMonths(4),
            "end_date" => today()->subMonths(4)->addDays(1)
        ]);

        // Create a finished team tournament (id: 4)
        Tournament::factory()
        ->for($gameTrackmania)
        ->create([
            "id" => 4,
            'name' => 'GG-LAN #17 Trackmania',
            "type" => "solo",
            "status" => "finished",
            "start_date" => today()->subMonths(4),
            "end_date" => today()->subMonths(4)->addDays(1)
        ]);
    }
}
