<?php
namespace Database\Seeders;

use App\Models\Team;
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

        // 1 registered team of User 2
        Team::factory()
            ->openTournament(1)
            ->full($captain)
            ->createQuietly();

        // 1 random pending team
        Team::factory()
            ->openTournament(1)
            ->full(pending: true)
            ->createQuietly();

        // 2 random not full team
        Team::factory()
            ->openTournament(1)
            ->notFull()
            ->createQuietly();

        Team::factory()
            ->openTournament(1)
            ->notFull()
            ->createQuietly();

        Team::factory()
            ->openTournament(1)
            ->full($captain, pending: true)
            ->createQuietly();

        Team::factory()
            ->finishedTournament(2)
            ->full($captain)
            ->createQuietly();

    }
}
