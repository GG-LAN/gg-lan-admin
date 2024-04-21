<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text(100),
            'places' => $this->faker->numberBetween(1, 5),
            'image' => $this->faker->imageUrl(640, 480),
        ];
    }
}
