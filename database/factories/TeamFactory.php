<?php
namespace Database\Factories;

use App\Models\Game;
use App\Models\Participation;
use App\Models\PurchasedPlace;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'          => $this->faker->name,
            'description'   => $this->faker->text(100),
            'image'         => $this->faker->imageUrl(640, 480),
            'tournament_id' => function () {
                return Tournament::factory()->create()->id;
            },
        ];
    }

    public function openTournament(int $id): Factory
    {
        $tournamentId = null;

        if (Tournament::where("id", $id)->exists()) {
            $tournamentId = $id;
        } else {
            $gameCsgo = Game::factory()->create([
                "name"   => "CS2",
                "places" => 5,
            ]);

            $tournament = Tournament::factory()
                ->for($gameCsgo)
                ->create([
                    "id"         => $id,
                    'name'       => 'GG-LAN #18 CS2',
                    "type"       => "team",
                    "status"     => "open",
                    "start_date" => today()->addMonths(2),
                    "end_date"   => today()->addMonths(2)->addDays(1),
                ]);

            $tournamentId = $tournament->id;
        }

        return $this->state(function (array $attributes) use ($tournamentId) {
            return [
                'tournament_id' => $tournamentId,
            ];
        });
    }

    public function finishedTournament(int $id): Factory
    {
        $tournamentId = null;

        if (Tournament::where("id", $id)->exists()) {
            $tournamentId = $id;
        } else {
            $gameCsgo = Game::factory()->create([
                "name"   => "CS2",
                "places" => 5,
            ]);

            $tournament = Tournament::factory()
                ->for($gameCsgo)
                ->create([
                    "id"         => $id,
                    'name'       => 'GG-LAN #17 CS2',
                    "type"       => "team",
                    "status"     => "finished",
                    "start_date" => today()->addMonths(2),
                    "end_date"   => today()->addMonths(2)->addDays(1),
                ]);

            $tournamentId = $tournament->id;
        }

        return $this->state(function (array $attributes) use ($tournamentId) {
            return [
                'tournament_id' => $tournamentId,
            ];
        });
    }

    public function notFull(?User $captain = null): Factory
    {
        return $this->afterCreating(function (Team $team) use ($captain) {
            if (! $captain) {
                $captain = User::factory()->create();
            }

            $players = User::factory(fake()->numberBetween(1, 3))->create();

            $team->users()->attach($captain, ["captain" => true]);

            PurchasedPlace::register($captain, $team->tournament);

            foreach ($players as $player) {
                $team->users()->attach($player, ["captain" => false]);

                PurchasedPlace::register($player, $team->tournament);
            }

            $team->registration_state = "not_full";

            $team->save();

            Participation::register($captain, $team->tournament, $team);

            foreach ($players as $player) {
                Participation::register($player, $team->tournament, $team);
            }
        });
    }

    public function full(?User $captain = null, bool $pending = false): Factory
    {
        return $this->afterCreating(function (Team $team) use ($captain, $pending) {
            if (! $captain) {
                $captain = User::factory()->create();
            }

            $players = User::factory(4)->create();

            $team->users()->attach($captain, ["captain" => true]);

            PurchasedPlace::register($captain, $team->tournament, true);

            foreach ($players as $player) {
                $team->users()->attach($player, ["captain" => false]);

                PurchasedPlace::register($player, $team->tournament, [true, false][fake()->numberBetween(0, 1)]);
            }

            $team->registration_state            = $pending ? "pending" : "registered";
            $team->registration_state_updated_at = now()->toDateTimeString();

            $team->save();

            Participation::register($captain, $team->tournament, $team);

            foreach ($players as $player) {
                Participation::register($player, $team->tournament, $team);
            }
        });
    }
}
