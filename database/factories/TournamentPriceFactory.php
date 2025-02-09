<?php
namespace Database\Factories;

use App\Models\Tournament;
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
            'name'          => 'GG-LAN #' . $fakeTournamentName,
            'tournament_id' => function () {
                $fakeTournamentName = $this->faker->numberBetween(1, 20);

                return Tournament::factory()->create([
                    'name'   => 'GG-LAN #' . $fakeTournamentName,
                    "type"   => "team",
                    "status" => "open",
                ])->id;
            },
            'active'        => true,
        ];
    }

    public function normalPrice(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                "price_id" => "price_1OtYNqAOwlBXXotYb0NBQROK",
                'price'    => '30,00 €',
                'type'     => 'normal',
            ];
        });
    }

    public function lastWeekPrice(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                "price_id" => "price_1OtYO6AOwlBXXotY6zNeBkVJ",
                'price'    => '35,00 €',
                'type'     => 'last_week',
            ];
        });
    }
}
