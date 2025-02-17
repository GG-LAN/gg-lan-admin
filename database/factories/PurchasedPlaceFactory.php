<?php
namespace Database\Factories;

use App\Models\TournamentPrice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchasedPlaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'             => function () {
                return User::factory()->create()->id;
            },
            'tournament_price_id' => function () {
                return TournamentPrice::factory()
                    ->normalPrice()
                    ->create()
                    ->id;
            },
        ];
    }
}
