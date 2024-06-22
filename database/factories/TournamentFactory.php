<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

class TournamentFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            'name'        => $this->faker->name,
            'description' => $this->faker->text(100),
            'game_id'     => Game::factory()->create(['places' => 5])->id,
            'start_date'  => "2021-05-29",
            'end_date'    => "2021-05-30",
            'places'      => $this->faker->numberBetween(4, 16),
            'cashprize'   => $this->faker->numberBetween(100, 500),
            'status'      => ['open', 'closed', 'finished'][rand(0, 2)],
            'image'       => $this->faker->imageUrl(640, 480),
            'type'        => ['solo', 'team'][rand(0, 1)]
        ];
    }
}