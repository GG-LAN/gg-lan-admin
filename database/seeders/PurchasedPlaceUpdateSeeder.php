<?php
namespace Database\Seeders;

use App\Models\PurchasedPlace;
use Illuminate\Database\Seeder;

class PurchasedPlaceUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (PurchasedPlace::all() as $payment) {
            $tournament = $payment->tournamentPrice->tournament;

            $payment->tournament()->associate($tournament);
            $payment->save();
        }
    }
}
