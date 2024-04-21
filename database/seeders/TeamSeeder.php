<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use App\Models\Tournament;
use App\Models\PurchasedPlace;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        // The created user in UserSeeder
        $captain = User::find(2);

        // Some solo users
        $soloUsers = User::factory(3)->create();
        
        // The tournaments created in TournamentSeeder
        $openTeamTournament = Tournament::find(1);
        $openSoloTournament = Tournament::find(2);
        
        $finishedTeamTournament = Tournament::find(3);
        $finishedSoloTournament = Tournament::find(4);
        
        
        /*
        |--------------------------------------------------------------------------
        | Open Team Tournament
        |--------------------------------------------------------------------------
        */
        
        // 1 registered team of User 2
        $team = Team::factory()
            ->for($openTeamTournament)
            ->hasAttached($captain, ["captain" => true])
            ->has(User::factory(4))
        ->create([
            "registration_state" => "registered",
        ]);

        // 1 random registered team
        Team::factory()
            ->for($openTeamTournament)
            ->hasAttached(User::factory(), ["captain" => true])
            ->has(User::factory(4))
        ->create([
            "registration_state" => "registered",
        ]);

        // 2 random not full team
        Team::factory(2)
            ->for($openTeamTournament)
            ->hasAttached(User::factory(), ["captain" => true])
            ->has(User::factory(3))
        ->create();

        
        /*
        |--------------------------------------------------------------------------
        | Open Solo Tournament
        |--------------------------------------------------------------------------
        */
        
        // Register 4 users for Open Solo Tournament
        $openSoloTournament->players()->attach($captain);
        foreach ($soloUsers as $soloUser) {
            $openSoloTournament->players()->attach($soloUser);
        }

        
        /*
        |--------------------------------------------------------------------------
        | Finished Team Tournament
        |--------------------------------------------------------------------------
        */
        
        // Register an old team of captain just for history
        Team::factory()
            ->for($finishedTeamTournament)
            ->hasAttached($captain, ["captain" => true])
            ->has(User::factory(4))
        ->create([
            "registration_state" => "registered",
        ]);

        /*
        |--------------------------------------------------------------------------
        | Finished Solo Tournament
        |--------------------------------------------------------------------------
        */
        
        // Register 4 users for Finished Solo Tournament
        $finishedSoloTournament->players()->attach($captain);
        foreach ($soloUsers as $soloUser) {
            $finishedSoloTournament->players()->attach($soloUser);
        }
        
        
        /*
        |--------------------------------------------------------------------------
        | Purchased Places for Tournaments
        |--------------------------------------------------------------------------
        */
        
        // Create purchased places for a team
        foreach ($team->users as $player) {
            PurchasedPlace::factory()->create([
                "user_id" => $player->id,
                "tournament_price_id" => $openTeamTournament->currentPrice()->id
            ]);
        }

        // Create purchased places for solo players
        foreach ($soloUsers as $player) {
            PurchasedPlace::factory()->create([
                "user_id" => $player->id,
                "tournament_price_id" => $openSoloTournament->currentPrice()->id
            ]);
        }
    }
}
