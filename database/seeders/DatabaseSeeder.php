<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;
use Database\Seeders\TeamSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\TournamentSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        $this->call([
            UserSeeder::class,
            TournamentSeeder::class,
            TeamSeeder::class,
        ]);

        Faq::factory(4)->create();
    }
}
