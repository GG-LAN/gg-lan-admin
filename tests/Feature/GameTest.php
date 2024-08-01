<?php

use App\ApiCode;
// use Tests\TestCase;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

it("can get games", function () {
    Game::factory(10)->create();

    $games = Game::all();

    $this->get('/api/games')
    ->assertOk()
    ->assertJson([
        "data" => $games->toArray()
    ]);
});

it("can get games with pagination", function () {
    Game::factory(10)->create();

    $item_per_page = 5;
    $games = Game::paginate($item_per_page);
    
    $response = $this->get('/api/games/paginate/'.$item_per_page);

    $result = json_decode($response->getContent(), true);
    
    $response->assertStatus(ApiCode::SUCCESS);

    $this->assertEquals($item_per_page, count($result['data']['data']));
});

it("can get a game", function () {
    $game = Game::factory()->create();

    $this->get('/api/games/'.$game->id)
    ->assertOk()
    ->assertJson([
        "data" => $game->toArray()
    ]);
});