<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Participation>
 */
class ParticipationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

        ];
    }

    public function registered()
    {
        return $this->state(function (array $attributes) {
            return [
                "status" => "registered",
            ];
        });
    }

    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                "status" => "pending",
            ];
        });
    }

    public function notFull()
    {
        return $this->state(function (array $attributes) {
            return [
                "status" => "not_full",
            ];
        });
    }
}
