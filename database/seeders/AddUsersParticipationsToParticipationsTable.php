<?php
namespace Database\Seeders;

use App\Models\Participation;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Database\Seeder;

class AddUsersParticipationsToParticipationsTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->teams->each(function (Team $team) use ($user) {
                $participation = new Participation;

                $participation->user()->associate($user);
                $participation->team()->associate($team);
                $participation->tournament()->associate($team->tournament);
                $participation->status = $team->registration_state;

                $participation->save();
            });

            $user->tournaments->each(function (Tournament $tournament) use ($user) {
                $participation = new Participation;

                $participation->user()->associate($user);
                $participation->tournament()->associate($tournament);
                $participation->status = "registered";

                $participation->save();
            });
        }
    }
}
