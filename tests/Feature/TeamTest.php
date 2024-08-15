<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\Team;
use App\Models\User;
use App\Models\Tournament;
use App\Models\TournamentPrice;


it("get_teams", function () {
    $teams = Team::factory(10)
                ->hasAttached(User::factory()->create(), ['captain' => true])
                ->create();

    $this->get('/api/teams')
    ->assertOk()
    ->assertJson([
        "data" => $teams->toArray()
    ]);
});

it("get_teams_with_pagination", function () {
    Team::factory(10)
        ->hasAttached(User::factory()->create(), ['captain' => true])
        ->create();

    $item_per_page = 5;
    $teams = Team::paginate($item_per_page);
    
    $response = $this->get('/api/teams/paginate/'.$item_per_page);

    $result = json_decode($response->getContent(), true);
    
    $response->assertOk();

    $this->assertEquals($item_per_page, count($result['data']['data']));
});

it("get_team", function () {
    $team = Team::factory()
                ->hasAttached(User::factory()->create(), ['captain' => true])
                ->create();

    $this->get('/api/teams/'.$team->id)
    ->assertOk()
    ->assertJson([
        "data" => $team->toArray()
    ]);
});

it("create_team", function () {
    $tournament = Tournament::factory()
    ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
        return [
            'name' => $tournament->name,
            'tournament_id' => $tournament->id,
            'type' => 'normal',
            'active' => true
        ];
    }), "prices")
    ->create([
        'type' => 'team',
        'status' => 'open',
        'places' => 4
    ]);

    $data = [
        "name"          => "Mangemort",
        "description"   => "On roule sur la concu",
        'tournament_id' => $tournament->id
    ];

    $user = User::factory()->create();
    
    $this->actingAs($user)->post('/api/teams/create', $data)
    ->assertCreated()
    ->assertJson([
        "data" => $data
    ]);
});

it("cant_create_team_on_closed_tournament", function () {
    $tournament = Tournament::factory()
    ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
        return [
            'name' => $tournament->name,
            'tournament_id' => $tournament->id,
            'type' => 'normal',
            'active' => true
        ];
    }), "prices")
    ->create([
        'type' => 'team',
        'status' => 'closed',
        'places' => 4
    ]);

    $data = [
        "name"          => "Mangemort",
        "description"   => "On roule sur la concu",
        'tournament_id' => $tournament->id
    ];

    $user = User::factory()->create();
    
    $this->actingAs($user)->post('/api/teams/create', $data)
    ->assertForbidden()
    ->assertJson([
        'status' => "error",
        'message' => __("responses.teams.register.cant_closed")
    ]);
});

it("cant_create_team_on_finished_tournament", function () {
    $tournament = Tournament::factory()
    ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
        return [
            'name' => $tournament->name,
            'tournament_id' => $tournament->id,
            'type' => 'normal',
            'active' => true
        ];
    }), "prices")
    ->create([
        'type' => 'team',
        'status' => 'finished',
        'places' => 4
    ]);

    $data = [
        "name"          => "Mangemort",
        "description"   => "On roule sur la concu",
        'tournament_id' => $tournament->id
    ];

    $user = User::factory()->create();
    
    $this->actingAs($user)->post('/api/teams/create', $data)
    ->assertForbidden()
    ->assertJson([
        'status' => "error",
        'message' => __("responses.teams.register.cant_closed")
    ]);
});

it("cant_create_team_on_solo_tournament", function () {
    $tournament = Tournament::factory()
    ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
        return [
            'name' => $tournament->name,
            'tournament_id' => $tournament->id,
            'type' => 'normal',
            'active' => true
        ];
    }), "prices")
    ->create([
        'type' => 'solo',
        'places' => 4
    ]);

    $data = [
        "name"          => "Mangemort",
        "description"   => "On roule sur la concu",
        'tournament_id' => $tournament->id
    ];

    $user = User::factory()->create();
    
    $this->actingAs($user)->post('/api/teams/create', $data)
    ->assertForbidden()
    ->assertJson([
        'status' => "error",
        'message' => __("responses.teams.register.cant_solo")
    ]);
});

it("can_update_team_as_captain", function () {
    $updated_data = [
        "name"          => "Mangemort updated",
        "description"   => "On roule sur la concu updated",
        'image'         => ""
    ];

    $user = User::factory()->create();

    $team = Team::factory()
        ->hasAttached($user, ['captain' => true])
        ->has(User::factory()->count(3))
        ->for(Tournament::factory()->create(["type" => "team", "status" => "open"]))
        ->create();
    
    $this->actingAs($user)->put('/api/teams/' . $team->id, $updated_data)
    ->assertOk()
    ->assertJson([
        "data" => $updated_data
    ]);
});

it("cant_update_team_as_not_captain_of_it", function () {
    $updated_data = [
        "name"          => "Mangemort updated",
        "description"   => "On roule sur la concu updated",
        'image'         => ""
    ];

    $user = User::factory()->create();

    $another_user = User::factory()->create();

    $team = Team::factory()
        ->hasAttached($another_user, ['captain' => true])
        ->has(User::factory()->count(3))
        ->for(Tournament::factory()->create(["type" => "team", "status" => "open"]))
        ->create();
    
    $this->actingAs($user)->put('/api/teams/' . $team->id, $updated_data)
    ->assertForbidden()
    ->assertJson([
        'status' => "error",
        'message' => __("responses.teams.not_captain")
    ]);
});

it("cant_update_team_if_not_in_team", function () {
    $updated_data = [
        "name"          => "Mangemort updated",
        "description"   => "On roule sur la concu updated",
        'image'         => ""
    ];

    $user = User::factory()->create();

    $another_user = User::factory()->create();

    $team = Team::factory()
        ->hasAttached($another_user, ['captain' => true])
        ->has(User::factory()->count(3))
        ->for(Tournament::factory()->create(["type" => "team", "status" => "open"]))
        ->create();
    
    $this->actingAs($user)->put('/api/teams/' . $team->id, $updated_data)
    ->assertForbidden()
    ->assertJson([
        'status' => "error",
        'message' => __("responses.teams.not_captain")
    ]);
});

it("cant_update_team_if_not_captain", function () {
    $updated_data = [
        "name"          => "Mangemort updated",
        "description"   => "On roule sur la concu updated",
        'image'         => ""
    ];

    $user = User::factory()->create();

    $team = Team::factory()
        ->hasAttached(User::factory()->create(), ['captain' => true])
        ->hasAttached($user)
        ->for(Tournament::factory()->create(["type" => "team", "status" => "open"]))
        ->create();
    
    $this->actingAs($user)->put('/api/teams/' . $team->id, $updated_data)
    ->assertForbidden()
    ->assertJson([
        'status' => "error",
        'message' => __("responses.teams.not_captain")
    ]);
});

it("team_captain_can_add_player_to_team", function () {
    // Tournament with a game of 5 places per team
    $tournament = Tournament::factory()
    ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
        return [
            'name' => $tournament->name,
            'tournament_id' => $tournament->id,
            'type' => 'normal',
            'active' => true
        ];
    }), "prices")
    ->create([
        "status" => "open",
        "type" => "team",
        "places" => 10,
        "game_id" => Game::factory()->create(["places" => 5])->id
    ]);
    
    $captain = User::factory()->create();
    
    $team = Team::factory()
        ->hasAttached($captain, ['captain' => true])
        ->hasAttached(User::factory()->count(2))
        ->for($tournament)
        ->create();

    $player = User::factory()->create();
    
    $this->actingAs($captain)->post('/api/teams/' . $team->id . '/addPlayer/' . $player->id)
    ->assertOk()
    ->assertJson([
        'status' => "success",
        'message' => __("responses.teams.player_added")
    ]);
});
    
it("player_can_add_himself_to_team", function () {
    // Tournament with a game of 5 places per team
    $tournament = Tournament::factory()
    ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
        return [
            'name' => $tournament->name,
            'tournament_id' => $tournament->id,
            'type' => 'normal',
            'active' => true
        ];
    }), "prices")
    ->create([
        "status" => "open",
        "type" => "team",
        "places" => 10,
        "game_id" => Game::factory()->create(["places" => 5])->id
    ]);
            
    $team = Team::factory()
        ->hasAttached(User::factory()->create(), ['captain' => true])
        ->hasAttached(User::factory()->count(2))
        ->for($tournament)
        ->create();

    $player = User::factory()->create();
    
    $this->actingAs($player)->post('/api/teams/' . $team->id . '/addPlayer/' . $player->id)
    ->assertOk()
    ->assertJson([
        'status' => "success",
        'message' => __("responses.teams.player_added")
    ]);
});

it("player_cant_add_himself_if_hes_already_in_the_team", function () {
    // Tournament with a game of 5 places per team
    $tournament = Tournament::factory()->create([
        "status" => "open",
        "type" => "team",
        "places" => 10,
        "game_id" => Game::factory()->create(["places" => 5])->id
    ]);
            
    $team = Team::factory()
        ->hasAttached(User::factory()->create(), ['captain' => true])
        ->hasAttached(User::factory()->count(3))
        ->for($tournament)
        ->create();

    $player = $team->users->where("pivot.captain", 0)->first();
    
    $this->actingAs($player)->post('/api/teams/' . $team->id . '/addPlayer/' . $player->id)
    ->assertForbidden()
    ->assertJson([
        'status' => "error",
        'message' => __("responses.teams.player_in_team")
    ]);
});

it("team_captain_cant_add_player_if_team_is_full", function () {
    // Tournament with a game of 5 places per team
    $tournament = Tournament::factory()->create([
        "status" => "open",
        "type" => "team",
        "places" => 10,
        "game_id" => Game::factory()->create(["places" => 5])->id
    ]);
    
    $captain = User::factory()->create();
    
    $team = Team::factory()
        ->hasAttached($captain, ['captain' => true])
        ->hasAttached(User::factory()->count(4))
        ->for($tournament)
        ->create(["registration_state" => Team::REGISTERED]);

    $player = User::factory()->create();
    
    $this->actingAs($captain)->post('/api/teams/' . $team->id . '/addPlayer/' . $player->id)
    ->assertForbidden()
    ->assertJson([
        'status' => "error",
        'message' => __("responses.teams.full")
    ]);
});

it("player_cant_add_himself_if_team_is_full", function () {
    // Tournament with a game of 5 places per team
    $tournament = Tournament::factory()->create([
        "status" => "open",
        "type" => "team",
        "places" => 10,
        "game_id" => Game::factory()->create(["places" => 5])->id
    ]);
            
    $team = Team::factory()
        ->hasAttached(User::factory()->create(), ['captain' => true])
        ->hasAttached(User::factory()->count(4))
        ->for($tournament)
        ->create(["registration_state" => Team::REGISTERED]);

    $player = User::factory()->create();
    
    $this->actingAs($player)->post('/api/teams/' . $team->id . '/addPlayer/' . $player->id)
    ->assertForbidden()
    ->assertJson([
        'status' => "error",
        'message' => __("responses.teams.full")
    ]);
});

it("cant_remove_player_from_team_if_not_captain_of_it", function () {
    // Tournament with a game of 5 places per team
    $tournament = Tournament::factory()->create([
        "status" => "open",
        "type" => "team",
        "places" => 10,
        "game_id" => Game::factory()->create(["places" => 5])->id
    ]);
            
    $team = Team::factory()
        ->hasAttached(User::factory()->create(), ['captain' => true])
        ->hasAttached(User::factory()->count(4))
        ->for($tournament)
        ->create(["registration_state" => Team::REGISTERED]);

    $player = $team->users->where("pivot.captain", 0)->first();
    
    $this->actingAs($player)->post('/api/teams/' . $team->id . '/removePlayer/' . $player->id)
    ->assertForbidden()
    ->assertJson([
        'status' => "error",
        'message' => __("responses.teams.not_captain")
    ]);
});

it("cant_remove_player_from_team_if_random_player", function () {
    // Tournament with a game of 5 places per team
    $tournament = Tournament::factory()->create([
        "status" => "open",
        "type" => "team",
        "places" => 10,
        "game_id" => Game::factory()->create(["places" => 5])->id
    ]);
            
    $team = Team::factory()
        ->hasAttached(User::factory()->create(), ['captain' => true])
        ->hasAttached(User::factory()->count(4))
        ->for($tournament)
        ->create(["registration_state" => Team::REGISTERED]);

    $player = User::factory()->create();
    
    $this->actingAs($player)->post('/api/teams/' . $team->id . '/removePlayer/' . $player->id)
    ->assertForbidden()
    ->assertJson([
        'status' => "error",
        'message' => __("responses.teams.not_captain")
    ]);
});

it("team_captain_can_remove_player_from_team", function () {
    // Tournament with a game of 5 places per team
    $tournament = Tournament::factory()
    ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
        return [
            'name' => $tournament->name,
            'tournament_id' => $tournament->id,
            'type' => 'normal',
            'active' => true
        ];
    }), "prices")
    ->create([
        "status" => "open",
        "type" => "team",
        "places" => 10,
        "game_id" => Game::factory()->create(["places" => 5])->id
    ]);
    
    $captain = User::factory()->create();
    
    $team = Team::factory()
        ->hasAttached($captain, ['captain' => true])
        ->hasAttached(User::factory()->count(4))
        ->for($tournament)
        ->create(["registration_state" => Team::REGISTERED]);

    $player = $team->users->where("pivot.captain", 0)->first();
    
    $this->actingAs($captain)->post('/api/teams/' . $team->id . '/removePlayer/' . $player->id)
    ->assertOk()
    ->assertJson([
        'status' => "success",
        'message' => __("responses.teams.player_removed")
    ]);
});

it("team_captain_cant_remove_captain_from_team", function () {
    // Tournament with a game of 5 places per team
    $tournament = Tournament::factory()->create([
        "status" => "open",
        "type" => "team",
        "places" => 10,
        "game_id" => Game::factory()->create(["places" => 5])->id
    ]);
    
    $captain = User::factory()->create();
    
    $team = Team::factory()
        ->hasAttached($captain, ['captain' => true])
        ->hasAttached(User::factory()->count(4))
        ->for($tournament)
        ->create(["registration_state" => Team::REGISTERED]);

    $player = $team->users->where("pivot.captain", 0)->first();
    
    $this->actingAs($captain)->post('/api/teams/' . $team->id . '/removePlayer/' . $captain->id)
    ->assertForbidden()
    ->assertJson([
        'status' => "error",
        'message' => __("responses.teams.cant_remove_captain")
    ]);
});

it("cant_remove_player_from_team_if_hes_not_in_team", function () {
    // Tournament with a game of 5 places per team
    $tournament = Tournament::factory()->create([
        "status" => "open",
        "type" => "team",
        "places" => 10,
        "game_id" => Game::factory()->create(["places" => 5])->id
    ]);
    
    $captain = User::factory()->create();
    
    $team = Team::factory()
        ->hasAttached($captain, ['captain' => true])
        ->hasAttached(User::factory()->count(4))
        ->for($tournament)
        ->create(["registration_state" => Team::REGISTERED]);

    $player = User::factory()->create();
    
    $this->actingAs($captain)->post('/api/teams/' . $team->id . '/removePlayer/' . $player->id)
    ->assertForbidden()
    ->assertJson([
        'status' => "error",
        'message' => __("responses.teams.player_not_in_team")
    ]);
});

it("can't create a team with a name that is already used", function () {
    $tournament = Tournament::factory()
    ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
        return [
            'name' => $tournament->name,
            'tournament_id' => $tournament->id,
            'type' => 'normal',
            'active' => true
        ];
    }), "prices")
    ->create(["type" => "team", "status" => "open"]);
    
    $dataTeamOK = [
        "name"          => "Mangemort",
        "description"   => "On roule sur la concu",
        'tournament_id' => $tournament->id,
        'image'         => ""
    ];

    $dataTeamKO = [
        "name"          => "Mangemort",
        "description"   => "",
        'tournament_id' => $tournament->id,
        'image'         => ""
    ];

    $user = User::factory()->create();
    
    $this->actingAs($user)->post('/api/teams/create', $dataTeamOK)
    ->assertCreated()
    ->assertJson([
        "data" => $dataTeamOK
    ]);

    $this->actingAs($user)->post('/api/teams/create', $dataTeamKO)
    ->assertUnprocessable()
    ->assertJson([
        "data" => [
            "name" => [
                __("validation.unique", ["attribute" => "name"])
            ]
        ]
    ]);
});

test("A purchased place is created for the captain after creating a team", function () {
    $tournament = Tournament::factory()
    ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
        return [
            'name' => $tournament->name,
            'tournament_id' => $tournament->id,
            'type' => 'normal',
            'active' => true
        ];
    }), "prices")
    ->create([
        'type' => 'team',
        'status' => 'open',
        'places' => 4
    ]);

    $data = [
        "name"          => "Mangemort",
        "description"   => "On roule sur la concu",
        'tournament_id' => $tournament->id
    ];

    $user = User::factory()->create();

    $this->actingAs($user)->post('/api/teams/create', $data);
    
    $this->assertDatabaseHas("purchased_places", [
        "user_id" => $user->id,
        'tournament_price_id' => $tournament->currentPrice()->id,
    ]);
});

test("A purchased place is created for the player that has been added to a team", function () {
    $tournament = Tournament::factory()
    ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
        return [
            'name' => $tournament->name,
            'tournament_id' => $tournament->id,
            'type' => 'normal',
            'active' => true
        ];
    }), "prices")
    ->create([
        'type' => 'team',
        'status' => 'open',
        'places' => 4
    ]);

    $team = Team::factory()
        ->hasAttached(User::factory()->create(), ['captain' => true])
        ->hasAttached(User::factory()->count(2))
        ->for($tournament)
        ->create();

    $player = User::factory()->create();
    
    $this->actingAs($player)->post('/api/teams/' . $team->id . '/addPlayer/' . $player->id);

    
    $this->assertDatabaseHas("purchased_places", [
        "user_id" => $player->id,
        'tournament_price_id' => $tournament->currentPrice()->id,
    ]);
});

test("Purchased place is delete for the player that has been removed from a team", function () {
    $tournament = Tournament::factory()
    ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
        return [
            'name' => $tournament->name,
            'tournament_id' => $tournament->id,
            'type' => 'normal',
            'active' => true
        ];
    }), "prices")
    ->create([
        'type' => 'team',
        'status' => 'open',
        'places' => 4
    ]);
    
    $captain = User::factory()->create();

    $team = Team::factory()
        ->hasAttached($captain, ['captain' => true])
        ->hasAttached(User::factory()->count(2))
        ->for($tournament)
        ->create();

    $player = User::factory()->create();
    
    $this->actingAs($captain)->post('/api/teams/' . $team->id . '/addPlayer/' . $player->id);
    
    $this->actingAs($captain)->post('/api/teams/' . $team->id . '/removePlayer/' . $player->id);

    $this->assertDatabaseMissing("purchased_places", [
        "user_id" => $player->id,
        'tournament_price_id' => $tournament->currentPrice()->id,
    ]);
});