<?php

namespace Database\Factories;

use App\Models\Tournament;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text(100),
            'image' => $this->faker->imageUrl(640, 480),
            'tournament_id' => function() {
                return Tournament::factory()->create()->id;
            }
        ];
    }
}
