<?php

use App\Models\Game;

it("can get games", function () {
    Game::factory(10)->create();

    $games = Game::all();

    $this->get('/api/games')
    ->assertOk()
    ->assertJson([
        "data" => $games->toArray()
    ]);
});

it("can get a game", function () {
    $game = Game::factory()->create();

    $this->get('/api/games/'.$game->id)
    ->assertOk()
    ->assertJson([
        "data" => $game->toArray()
    ]);
});
