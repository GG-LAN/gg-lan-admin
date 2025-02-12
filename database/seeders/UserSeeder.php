<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create admin user (id: 1)
        User::factory()->create([
            'email'    => 'admin@admin.com',
            'password' => bcrypt('admin'),
            'admin'    => 1,
        ]);

        // Create normal user (id: 2)
        User::factory()
            ->openSoloTournament(3)
            ->createQuietly([
                'email'    => 'test@test.com',
                'password' => bcrypt('password'),
            ]);

        // Some solo users
        User::factory(3)
            ->openSoloTournament(3)
            ->finishedSoloTournament(4)
            ->createQuietly();
    }
}
