<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "address"   => "230 Rue Roland Garros, 29490 Guipavas, France",
            "longitude" => -4.412462,
            "latitude"  => 48.4433668,
        ];
    }
}
