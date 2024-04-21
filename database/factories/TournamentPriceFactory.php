<?php

namespace Database\Factories;

// use App\Models\Tournament;
use Illuminate\Database\Eloquent\Factories\Factory;

class TournamentPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $fakeTournamentName = $this->faker->numberBetween(1, 20);
        
        return [
            'name' => 'GG-LAN #' . $fakeTournamentName,
            'price_id' => 'price_1OtYNqAOwlBXXotYb0NBQROK',
            // 'tournament_id' => Tournament::factory([
            //     'name' => 'GG-LAN #' . $fakeTournamentName,
            //     "type" => "team",
            //     "status" => "open"
            // ])->create()->id,
            'type' => 'normal',
            'active' => true
        ];
    }
}
