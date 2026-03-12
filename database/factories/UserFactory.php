<?php
namespace Database\Factories;

use App\Models\FaceitAccount;
use App\Models\Game;
use App\Models\Participation;
use App\Models\PurchasedPlace;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'              => $this->faker->name(),
            'email'             => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'birth_date'        => $this->faker->dateTimeThisCentury->format('Y-m-d'),
            'password'          => bcrypt("secret"),
            'remember_token'    => Str::random(10),
            'pseudo'            => $this->faker->name,
        ];
    }

    public function configure(): Factory
    {
        return $this->afterCreating(function (User $user) {
            FaceitAccount::create([
                "user_id"     => $user->id,
                "nickname"    => $user->pseudo,
                "player_id"   => $this->faker->uuid(),
                "steam_id_64" => $this->faker->randomNumber(8, true),
                "elo_cs2"     => $this->faker->randomNumber(4, true),
            ]);
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    public function openSoloTournament(int $id): Factory
    {
        return $this->afterCreating(function (User $user) use ($id) {
            $tournament = Tournament::where("id", $id);

            if ($tournament->exists()) {
                $tournament = $tournament->first();
            } else {
                $gameTrackmania = Game::factory()->create([
                    "name"   => "Trackmania",
                    "places" => 1,
                ]);

                $tournament = Tournament::factory()
                    ->for($gameTrackmania)
                    ->create([
                        "id"         => $id,
                        'name'       => 'GG-LAN #18 Trackmania',
                        "type"       => "solo",
                        "status"     => "open",
                        "start_date" => today()->addMonths(2),
                        "end_date"   => today()->addMonths(2)->addDays(1),
                    ]);
            }

            $tournament->players()->attach($user);

            PurchasedPlace::register($user, $tournament, [true, false][fake()->numberBetween(0, 1)]);
            Participation::register($user, $tournament);
        });
    }

    public function finishedSoloTournament(int $id): Factory
    {
        return $this->afterCreating(function (User $user) use ($id) {
            $tournament = Tournament::where("id", $id);

            if ($tournament->exists()) {
                $tournament = $tournament->first();
            } else {
                $gameTrackmania = Game::factory()->create([
                    "name"   => "Trackmania",
                    "places" => 1,
                ]);

                $tournament = Tournament::factory()
                    ->for($gameTrackmania)
                    ->create([
                        "id"         => $id,
                        'name'       => 'GG-LAN #17 Trackmania',
                        "type"       => "solo",
                        "status"     => "finished",
                        "start_date" => today()->subMonths(4),
                        "end_date"   => today()->subMonths(4)->addDays(1),
                    ]);
            }

            $tournament->players()->attach($user);

            PurchasedPlace::register($user, $tournament, [true, false][fake()->numberBetween(0, 1)]);
            Participation::register($user, $tournament);
        });
    }
}
