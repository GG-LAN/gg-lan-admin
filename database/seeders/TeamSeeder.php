<?php
namespace Database\Seeders;

use App\Models\PurchasedPlace;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
        $team1 = Team::factory()
            ->for($openTeamTournament)
            ->hasAttached($captain, ["captain" => true])
            ->has(User::factory(4))
            ->createQuietly([
                "registration_state" => "registered",
            ]);

        // 1 random registered team
        $team2 = Team::factory()
            ->for($openTeamTournament)
            ->hasAttached(User::factory(), ["captain" => true])
            ->has(User::factory(4))
            ->createQuietly([
                "registration_state" => "registered",
            ]);

        // 2 random not full team
        $team3 = Team::factory()
            ->for($openTeamTournament)
            ->hasAttached(User::factory(), ["captain" => true])
            ->has(User::factory(3))
            ->create();

        $team4 = Team::factory()
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
            // Disable fire events for TournamentUserObserver
            $openSoloTournament::unsetEventDispatcher();

            $openSoloTournament->players()->attach($soloUser);
        }

        /*
        |--------------------------------------------------------------------------
        | Finished Team Tournament
        |--------------------------------------------------------------------------
        */

        // Register an old team of captain just for history
        $team5 = Team::factory()
            ->for($finishedTeamTournament)
            ->hasAttached($captain, ["captain" => true])
            ->has(User::factory(4))
            ->createQuietly([
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

        // Create purchased places for teams
        foreach ($team1->users as $player) {
            PurchasedPlace::register($player, $openTeamTournament, paid: true);
        }

        foreach ($team2->users as $player) {
            PurchasedPlace::register($player, $openTeamTournament);
        }

        foreach ($team3->users as $player) {
            PurchasedPlace::register($player, $openTeamTournament);
        }

        foreach ($team4->users as $player) {
            PurchasedPlace::register($player, $openTeamTournament, paid: true);
        }

        foreach ($team5->users as $player) {
            PurchasedPlace::register($player, $openTeamTournament);
        }

        // Create purchased places for solo players
        foreach ($soloUsers as $player) {
            PurchasedPlace::register($player, $openSoloTournament);
        }
    }
}
