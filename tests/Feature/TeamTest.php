<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\TournamentPrice;
use App\Models\User;

it("get_teams", function () {
    $teams = Team::factory(10)
        ->hasAttached(User::factory()->createQuietly(), ['captain' => true])
        ->createQuietly();

    $this->get('/api/teams')
        ->assertOk()
        ->assertJson([
            "data" => $teams->toArray(),
        ]);
});

it("get_teams_with_pagination", function () {
    Team::factory(10)
        ->hasAttached(User::factory()->createQuietly(), ['captain' => true])
        ->createQuietly();

    $item_per_page = 5;
    $teams = Team::paginate($item_per_page);

    $response = $this->get('/api/teams/paginate/' . $item_per_page);

    $result = json_decode($response->getContent(), true);

    $response->assertOk();

    $this->assertEquals($item_per_page, count($result['data']['data']));
});

it("get_team", function () {
    $team = Team::factory()
        ->hasAttached(User::factory()->createQuietly(), ['captain' => true])
        ->createQuietly();

    $this->get('/api/teams/' . $team->id)
        ->assertOk()
        ->assertJson([
            "data" => $team->toArray(),
        ]);
});

it("create_team", function () {
    $tournament = Tournament::factory()
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name' => $tournament->name,
                'tournament_id' => $tournament->id,
                'type' => 'normal',
                'active' => true,
            ];
        }), "prices")
        ->createQuietly([
            'type' => 'team',
            'status' => 'open',
            'places' => 4,
        ]);

    $data = [
        "name" => "Mangemort",
        "description" => "On roule sur la concu",
        'tournament_id' => $tournament->id,
    ];

    $user = User::factory()->createQuietly();

    $this->actingAs($user)->post('/api/teams/create', $data)
        ->assertCreated()
        ->assertJson([
            "data" => $data,
        ]);
});

test("creating a team also adds you as captain of it", function () {
    $tournament = Tournament::factory()
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name' => $tournament->name,
                'tournament_id' => $tournament->id,
                'type' => 'normal',
                'active' => true,
            ];
        }), "prices")
        ->createQuietly([
            'type' => 'team',
            'status' => 'open',
            'places' => 4,
        ]);

    $data = [
        "name" => "Mangemort",
        "description" => "On roule sur la concu",
        'tournament_id' => $tournament->id,
    ];

    $user = User::factory()->createQuietly();

    $response = $this->actingAs($user)->post('/api/teams/create', $data);

    $this->assertDatabaseHas("team_user", [
        "user_id" => $user->id,
        "team_id" => $response->decodeResponseJson()["data"]["id"],
        "captain" => 1,
    ]);
});

test("A purchased place is created for the captain after creating a team", function () {
    $tournament = Tournament::factory()
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name' => $tournament->name,
                'tournament_id' => $tournament->id,
                'type' => 'normal',
                'active' => true,
            ];
        }), "prices")
        ->createQuietly([
            'type' => 'team',
            'status' => 'open',
            'places' => 4,
        ]);

    $data = [
        "name" => "Mangemort",
        "description" => "On roule sur la concu",
        'tournament_id' => $tournament->id,
    ];

    $user = User::factory()->createQuietly();

    $this->actingAs($user)->post('/api/teams/create', $data);

    $this->assertDatabaseHas("purchased_places", [
        "user_id" => $user->id,
        'tournament_price_id' => $tournament->currentPrice()->id,
        "paid" => false,
    ]);
});

test("A purchased place is created for the player that has been added to a team", function () {
    $tournament = Tournament::factory()
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name' => $tournament->name,
                'tournament_id' => $tournament->id,
                'type' => 'normal',
                'active' => true,
            ];
        }), "prices")
        ->createQuietly([
            'type' => 'team',
            'status' => 'open',
            'places' => 4,
        ]);

    $team = Team::factory()
        ->hasAttached(User::factory()->createQuietly(), ['captain' => true])
        ->hasAttached(User::factory()->count(2))
        ->for($tournament)
        ->createQuietly();

    $player = User::factory()->createQuietly();

    $this->actingAs($player)->post('/api/teams/' . $team->id . '/addPlayer/' . $player->id);

    $this->assertDatabaseHas("purchased_places", [
        "user_id" => $player->id,
        'tournament_price_id' => $tournament->currentPrice()->id,
        "paid" => false,
    ]);
});

test("Purchased place is delete for the player that has been removed from a team", function () {
    $tournament = Tournament::factory()
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name' => $tournament->name,
                'tournament_id' => $tournament->id,
                'type' => 'normal',
                'active' => true,
            ];
        }), "prices")
        ->createQuietly([
            'type' => 'team',
            'status' => 'open',
            'places' => 4,
        ]);

    $captain = User::factory()->createQuietly();

    $team = Team::factory()
        ->hasAttached($captain, ['captain' => true])
        ->hasAttached(User::factory()->count(2))
        ->for($tournament)
        ->createQuietly();

    $player = User::factory()->createQuietly();

    $this->actingAs($captain)->post('/api/teams/' . $team->id . '/addPlayer/' . $player->id);

    $this->actingAs($captain)->post('/api/teams/' . $team->id . '/removePlayer/' . $player->id);

    $this->assertDatabaseMissing("purchased_places", [
        "user_id" => $player->id,
        'tournament_price_id' => $tournament->currentPrice()->id,
    ]);
});

it("cant_create_team_on_closed_tournament", function () {
    $tournament = Tournament::factory()
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name' => $tournament->name,
                'tournament_id' => $tournament->id,
                'type' => 'normal',
                'active' => true,
            ];
        }), "prices")
        ->createQuietly([
            'type' => 'team',
            'status' => 'closed',
            'places' => 4,
        ]);

    $data = [
        "name" => "Mangemort",
        "description" => "On roule sur la concu",
        'tournament_id' => $tournament->id,
    ];

    $user = User::factory()->createQuietly();

    $this->actingAs($user)->post('/api/teams/create', $data)
        ->assertForbidden()
        ->assertJson([
            'status' => "error",
            'message' => __("responses.team.register.cant_closed"),
        ]);
});

it("cant_create_team_on_finished_tournament", function () {
    $tournament = Tournament::factory()
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name' => $tournament->name,
                'tournament_id' => $tournament->id,
                'type' => 'normal',
                'active' => true,
            ];
        }), "prices")
        ->createQuietly([
            'type' => 'team',
            'status' => 'finished',
            'places' => 4,
        ]);

    $data = [
        "name" => "Mangemort",
        "description" => "On roule sur la concu",
        'tournament_id' => $tournament->id,
    ];

    $user = User::factory()->createQuietly();

    $this->actingAs($user)->post('/api/teams/create', $data)
        ->assertForbidden()
        ->assertJson([
            'status' => "error",
            'message' => __("responses.team.register.cant_closed"),
        ]);
});

it("cant_create_team_on_solo_tournament", function () {
    $tournament = Tournament::factory()
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name' => $tournament->name,
                'tournament_id' => $tournament->id,
                'type' => 'normal',
                'active' => true,
            ];
        }), "prices")
        ->createQuietly([
            'type' => 'solo',
            'places' => 4,
        ]);

    $data = [
        "name" => "Mangemort",
        "description" => "On roule sur la concu",
        'tournament_id' => $tournament->id,
    ];

    $user = User::factory()->createQuietly();

    $this->actingAs($user)->post('/api/teams/create', $data)
        ->assertForbidden()
        ->assertJson([
            'status' => "error",
            'message' => __("responses.team.register.cant_solo"),
        ]);
});

it("can_update_team_as_captain", function () {
    $updated_data = [
        "name" => "Mangemort updated",
        "description" => "On roule sur la concu updated",
        'image' => "",
    ];

    $user = User::factory()->createQuietly();

    $team = Team::factory()
        ->hasAttached($user, ['captain' => true])
        ->has(User::factory()->count(3))
        ->for(Tournament::factory()->createQuietly(["type" => "team", "status" => "open"]))
        ->createQuietly();

    $this->actingAs($user)->put('/api/teams/' . $team->id, $updated_data)
        ->assertOk()
        ->assertJson([
            "data" => $updated_data,
        ]);
});

it("cant_update_team_as_not_captain_of_it", function () {
    $updated_data = [
        "name" => "Mangemort updated",
        "description" => "On roule sur la concu updated",
        'image' => "",
    ];

    $user = User::factory()->createQuietly();

    $another_user = User::factory()->createQuietly();

    $team = Team::factory()
        ->hasAttached($another_user, ['captain' => true])
        ->has(User::factory()->count(3))
        ->for(Tournament::factory()->createQuietly(["type" => "team", "status" => "open"]))
        ->createQuietly();

    $this->actingAs($user)->put('/api/teams/' . $team->id, $updated_data)
        ->assertForbidden()
        ->assertJson([
            'status' => "error",
            'message' => __("responses.team.not_captain"),
        ]);
});

it("cant_update_team_if_not_in_team", function () {
    $updated_data = [
        "name" => "Mangemort updated",
        "description" => "On roule sur la concu updated",
        'image' => "",
    ];

    $user = User::factory()->createQuietly();

    $another_user = User::factory()->createQuietly();

    $team = Team::factory()
        ->hasAttached($another_user, ['captain' => true])
        ->has(User::factory()->count(3))
        ->for(Tournament::factory()->createQuietly(["type" => "team", "status" => "open"]))
        ->createQuietly();

    $this->actingAs($user)->put('/api/teams/' . $team->id, $updated_data)
        ->assertForbidden()
        ->assertJson([
            'status' => "error",
            'message' => __("responses.team.not_captain"),
        ]);
});

it("cant_update_team_if_not_captain", function () {
    $updated_data = [
        "name" => "Mangemort updated",
        "description" => "On roule sur la concu updated",
        'image' => "",
    ];

    $user = User::factory()->createQuietly();

    $team = Team::factory()
        ->hasAttached(User::factory()->createQuietly(), ['captain' => true])
        ->hasAttached($user)
        ->for(Tournament::factory()->createQuietly(["type" => "team", "status" => "open"]))
        ->createQuietly();

    $this->actingAs($user)->put('/api/teams/' . $team->id, $updated_data)
        ->assertForbidden()
        ->assertJson([
            'status' => "error",
            'message' => __("responses.team.not_captain"),
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
                'active' => true,
            ];
        }), "prices")
        ->createQuietly([
            "status" => "open",
            "type" => "team",
            "places" => 10,
            "game_id" => Game::factory()->createQuietly(["places" => 5])->id,
        ]);

    $captain = User::factory()->createQuietly();

    $team = Team::factory()
        ->hasAttached($captain, ['captain' => true])
        ->hasAttached(User::factory()->count(2))
        ->for($tournament)
        ->createQuietly();

    $player = User::factory()->createQuietly();

    $this->actingAs($captain)->post('/api/teams/' . $team->id . '/addPlayer/' . $player->id)
        ->assertOk()
        ->assertJson([
            'status' => "success",
            'message' => __("responses.team.player_added"),
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
                'active' => true,
            ];
        }), "prices")
        ->createQuietly([
            "status" => "open",
            "type" => "team",
            "places" => 10,
            "game_id" => Game::factory()->createQuietly(["places" => 5])->id,
        ]);

    $team = Team::factory()
        ->hasAttached(User::factory()->createQuietly(), ['captain' => true])
        ->hasAttached(User::factory()->count(2))
        ->for($tournament)
        ->createQuietly();

    $player = User::factory()->createQuietly();

    $this->actingAs($player)->post('/api/teams/' . $team->id . '/addPlayer/' . $player->id)
        ->assertOk()
        ->assertJson([
            'status' => "success",
            'message' => __("responses.team.player_added"),
        ]);
});

it("player_cant_add_himself_if_hes_already_in_the_team", function () {
    // Tournament with a game of 5 places per team
    $tournament = Tournament::factory()->createQuietly([
        "status" => "open",
        "type" => "team",
        "places" => 10,
        "game_id" => Game::factory()->createQuietly(["places" => 5])->id,
    ]);

    $team = Team::factory()
        ->hasAttached(User::factory()->createQuietly(), ['captain' => true])
        ->hasAttached(User::factory()->count(3))
        ->for($tournament)
        ->createQuietly();

    $player = $team->users->where("pivot.captain", 0)->first();

    $this->actingAs($player)->post('/api/teams/' . $team->id . '/addPlayer/' . $player->id)
        ->assertForbidden()
        ->assertJson([
            'status' => "error",
            'message' => __("responses.team.player_in_team"),
        ]);
});

it("team_captain_cant_add_player_if_team_is_full", function () {
    // Tournament with a game of 5 places per team
    $tournament = Tournament::factory()->createQuietly([
        "status" => "open",
        "type" => "team",
        "places" => 10,
        "game_id" => Game::factory()->createQuietly(["places" => 5])->id,
    ]);

    $captain = User::factory()->createQuietly();

    $team = Team::factory()
        ->hasAttached($captain, ['captain' => true])
        ->hasAttached(User::factory()->count(4))
        ->for($tournament)
        ->createQuietly(["registration_state" => Team::REGISTERED]);

    $player = User::factory()->createQuietly();

    $this->actingAs($captain)->post('/api/teams/' . $team->id . '/addPlayer/' . $player->id)
        ->assertForbidden()
        ->assertJson([
            'status' => "error",
            'message' => __("responses.team.full"),
        ]);
});

it("player_cant_add_himself_if_team_is_full", function () {
    // Tournament with a game of 5 places per team
    $tournament = Tournament::factory()->createQuietly([
        "status" => "open",
        "type" => "team",
        "places" => 10,
        "game_id" => Game::factory()->createQuietly(["places" => 5])->id,
    ]);

    $team = Team::factory()
        ->hasAttached(User::factory()->createQuietly(), ['captain' => true])
        ->hasAttached(User::factory()->count(4))
        ->for($tournament)
        ->createQuietly(["registration_state" => Team::REGISTERED]);

    $player = User::factory()->createQuietly();

    $this->actingAs($player)->post('/api/teams/' . $team->id . '/addPlayer/' . $player->id)
        ->assertForbidden()
        ->assertJson([
            'status' => "error",
            'message' => __("responses.team.full"),
        ]);
});

it("cant_remove_player_from_team_if_not_captain_of_it", function () {
    // Tournament with a game of 5 places per team
    $tournament = Tournament::factory()->createQuietly([
        "status" => "open",
        "type" => "team",
        "places" => 10,
        "game_id" => Game::factory()->createQuietly(["places" => 5])->id,
    ]);

    $team = Team::factory()
        ->hasAttached(User::factory()->createQuietly(), ['captain' => true])
        ->hasAttached(User::factory()->count(4))
        ->for($tournament)
        ->createQuietly(["registration_state" => Team::REGISTERED]);

    $player = $team->users->where("pivot.captain", 0)->first();

    $this->actingAs($player)->post('/api/teams/' . $team->id . '/removePlayer/' . $player->id)
        ->assertForbidden()
        ->assertJson([
            'status' => "error",
            'message' => __("responses.team.not_captain"),
        ]);
});

it("cant_remove_player_from_team_if_random_player", function () {
    // Tournament with a game of 5 places per team
    $tournament = Tournament::factory()->createQuietly([
        "status" => "open",
        "type" => "team",
        "places" => 10,
        "game_id" => Game::factory()->createQuietly(["places" => 5])->id,
    ]);

    $team = Team::factory()
        ->hasAttached(User::factory()->createQuietly(), ['captain' => true])
        ->hasAttached(User::factory()->count(4))
        ->for($tournament)
        ->createQuietly(["registration_state" => Team::REGISTERED]);

    $player = User::factory()->createQuietly();

    $this->actingAs($player)->post('/api/teams/' . $team->id . '/removePlayer/' . $player->id)
        ->assertForbidden()
        ->assertJson([
            'status' => "error",
            'message' => __("responses.team.not_captain"),
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
                'active' => true,
            ];
        }), "prices")
        ->createQuietly([
            "status" => "open",
            "type" => "team",
            "places" => 10,
            "game_id" => Game::factory()->createQuietly(["places" => 5])->id,
        ]);

    $captain = User::factory()->createQuietly();

    $team = Team::factory()
        ->hasAttached($captain, ['captain' => true])
        ->hasAttached(User::factory()->count(4))
        ->for($tournament)
        ->createQuietly(["registration_state" => Team::REGISTERED]);

    $player = $team->users->where("pivot.captain", 0)->first();

    $this->actingAs($captain)->post('/api/teams/' . $team->id . '/removePlayer/' . $player->id)
        ->assertOk()
        ->assertJson([
            'status' => "success",
            'message' => __("responses.team.player_removed"),
        ]);
});

it("team_captain_cant_remove_captain_from_team", function () {
    // Tournament with a game of 5 places per team
    $tournament = Tournament::factory()->createQuietly([
        "status" => "open",
        "type" => "team",
        "places" => 10,
        "game_id" => Game::factory()->createQuietly(["places" => 5])->id,
    ]);

    $captain = User::factory()->createQuietly();

    $team = Team::factory()
        ->hasAttached($captain, ['captain' => true])
        ->hasAttached(User::factory()->count(4))
        ->for($tournament)
        ->createQuietly(["registration_state" => Team::REGISTERED]);

    $player = $team->users->where("pivot.captain", 0)->first();

    $this->actingAs($captain)->post('/api/teams/' . $team->id . '/removePlayer/' . $captain->id)
        ->assertForbidden()
        ->assertJson([
            'status' => "error",
            'message' => __("responses.team.cant_remove_captain"),
        ]);
});

it("cant_remove_player_from_team_if_hes_not_in_team", function () {
    // Tournament with a game of 5 places per team
    $tournament = Tournament::factory()->createQuietly([
        "status" => "open",
        "type" => "team",
        "places" => 10,
        "game_id" => Game::factory()->createQuietly(["places" => 5])->id,
    ]);

    $captain = User::factory()->createQuietly();

    $team = Team::factory()
        ->hasAttached($captain, ['captain' => true])
        ->hasAttached(User::factory()->count(4))
        ->for($tournament)
        ->createQuietly(["registration_state" => Team::REGISTERED]);

    $player = User::factory()->createQuietly();

    $this->actingAs($captain)->post('/api/teams/' . $team->id . '/removePlayer/' . $player->id)
        ->assertForbidden()
        ->assertJson([
            'status' => "error",
            'message' => __("responses.team.player_not_in_team"),
        ]);
});

it("can't create a team with a name that is already used", function () {
    $tournament = Tournament::factory()
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name' => $tournament->name,
                'tournament_id' => $tournament->id,
                'type' => 'normal',
                'active' => true,
            ];
        }), "prices")
        ->createQuietly(["type" => "team", "status" => "open"]);

    $dataTeamOK = [
        "name" => "Mangemort",
        "description" => "On roule sur la concu",
        'tournament_id' => $tournament->id,
        'image' => "",
    ];

    $dataTeamKO = [
        "name" => "Mangemort",
        "description" => "",
        'tournament_id' => $tournament->id,
        'image' => "",
    ];

    $user = User::factory()->createQuietly();

    $this->actingAs($user)->post('/api/teams/create', $dataTeamOK)
        ->assertCreated()
        ->assertJson([
            "data" => $dataTeamOK,
        ]);

    $this->actingAs($user)->post('/api/teams/create', $dataTeamKO)
        ->assertUnprocessable()
        ->assertJson([
            "data" => [
                "name" => [
                    __("validation.unique", ["attribute" => "name"]),
                ],
            ],
        ]);
});

test("Has captain, assert that team registration state change to 'registered' when team become full and it remains enough tournament places", function () {
    $tournament = Tournament::factory()
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name' => $tournament->name,
                'tournament_id' => $tournament->id,
                'type' => 'normal',
                'active' => true,
            ];
        }), "prices")
        ->createQuietly([
            "status" => "open",
            "type" => "team",
            "places" => 10,
            "game_id" => Game::factory()->createQuietly(["places" => 5])->id,
        ]);

    $captain = User::factory()->createQuietly();

    $lastPlayer = User::factory()->createQuietly();

    // Create a team with 4 players total.
    $team = Team::factory()
        ->hasAttached($captain, ['captain' => true])
        ->hasAttached(User::factory()->count(3))
        ->for($tournament)
        ->createQuietly(["registration_state" => Team::NOT_FULL]);

    $this->actingAs($captain)->post('/api/teams/' . $team->id . '/addPlayer/' . $lastPlayer->id);

    $this->assertDatabaseHas("teams", [
        "id" => $team->id,
        "registration_state" => Team::REGISTERED,
    ]);
});

test("Has captain, assert that team registration state change to 'pending' when team become full and tournament is full", function () {
    $tournament = Tournament::factory()
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name' => $tournament->name,
                'tournament_id' => $tournament->id,
                'type' => 'normal',
                'active' => true,
            ];
        }), "prices")
        ->createQuietly([
            "status" => "open",
            "type" => "team",
            "places" => 1,
            "game_id" => Game::factory()->createQuietly(["places" => 5])->id,
        ]);

    Team::factory()
        ->for($tournament)
        ->createQuietly([
            "registration_state" => Team::REGISTERED,
        ]);

    $captain = User::factory()->createQuietly();

    $lastPlayer = User::factory()->createQuietly();

    // Create a team with 4 players total.
    $team = Team::factory()
        ->hasAttached($captain, ['captain' => true])
        ->hasAttached(User::factory()->count(3))
        ->for($tournament)
        ->createQuietly(["registration_state" => Team::NOT_FULL]);

    $this->actingAs($captain)->post('/api/teams/' . $team->id . '/addPlayer/' . $lastPlayer->id);

    $this->assertDatabaseHas("teams", [
        "id" => $team->id,
        "registration_state" => Team::PENDING,
    ]);
});

test("Has random last player, assert that team registration state change to 'registered' when team become full and it remains enough tournament places", function () {
    $tournament = Tournament::factory()
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name' => $tournament->name,
                'tournament_id' => $tournament->id,
                'type' => 'normal',
                'active' => true,
            ];
        }), "prices")
        ->createQuietly([
            "status" => "open",
            "type" => "team",
            "places" => 10,
            "game_id" => Game::factory()->createQuietly(["places" => 5])->id,
        ]);

    $lastPlayer = User::factory()->createQuietly();

    // Create a team with 4 players total.
    $team = Team::factory()
        ->hasAttached(User::factory()->createQuietly(), ['captain' => true])
        ->hasAttached(User::factory()->count(3))
        ->for($tournament)
        ->createQuietly(["registration_state" => Team::NOT_FULL]);

    $this->actingAs($lastPlayer)->post('/api/teams/' . $team->id . '/addPlayer/' . $lastPlayer->id);

    $this->assertDatabaseHas("teams", [
        "id" => $team->id,
        "registration_state" => Team::REGISTERED,
    ]);
});

test("Has random last player, assert that team registration state change to 'registered' when team become full and tournament is full", function () {
    $tournament = Tournament::factory()
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name' => $tournament->name,
                'tournament_id' => $tournament->id,
                'type' => 'normal',
                'active' => true,
            ];
        }), "prices")
        ->createQuietly([
            "status" => "open",
            "type" => "team",
            "places" => 1,
            "game_id" => Game::factory()->createQuietly(["places" => 5])->id,
        ]);

    Team::factory()
        ->for($tournament)
        ->createQuietly([
            "registration_state" => Team::REGISTERED,
        ]);

    $lastPlayer = User::factory()->createQuietly();

    // Create a team with 4 players total.
    $team = Team::factory()
        ->hasAttached(User::factory()->createQuietly(), ['captain' => true])
        ->hasAttached(User::factory()->count(3))
        ->for($tournament)
        ->createQuietly(["registration_state" => Team::NOT_FULL]);

    $this->actingAs($lastPlayer)->post('/api/teams/' . $team->id . '/addPlayer/' . $lastPlayer->id);

    $this->assertDatabaseHas("teams", [
        "id" => $team->id,
        "registration_state" => Team::PENDING,
    ]);
});

test("Has captain, assert that removing a player from the team that is full and registered will also update the registration state of the team ", function () {
    $tournament = Tournament::factory()
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name' => $tournament->name,
                'tournament_id' => $tournament->id,
                'type' => 'normal',
                'active' => true,
            ];
        }), "prices")
        ->createQuietly([
            "status" => "open",
            "type" => "team",
            "places" => 10,
            "game_id" => Game::factory()->createQuietly(["places" => 5])->id,
        ]);

    $captain = User::factory()->createQuietly();

    $player = User::factory()->createQuietly();

    // Create a full team.
    $team = Team::factory()
        ->hasAttached($captain, ['captain' => true])
        ->hasAttached(User::factory()->count(3))
        ->hasAttached($player)
        ->for($tournament)
        ->createQuietly(["registration_state" => Team::REGISTERED]);

    $this->actingAs($captain)->post('/api/teams/' . $team->id . '/removePlayer/' . $player->id);

    $this->assertDatabaseHas("teams", [
        "id" => $team->id,
        "registration_state" => Team::NOT_FULL,
    ]);
});

test("Has captain, assert that removing a player from the team that is full and pending will also update the registration state of the team ", function () {
    $tournament = Tournament::factory()
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name' => $tournament->name,
                'tournament_id' => $tournament->id,
                'type' => 'normal',
                'active' => true,
            ];
        }), "prices")
        ->createQuietly([
            "status" => "open",
            "type" => "team",
            "places" => 1,
            "game_id" => Game::factory()->createQuietly(["places" => 5])->id,
        ]);

    $captain = User::factory()->createQuietly();

    $player = User::factory()->createQuietly();

    // Create a full team.
    $team = Team::factory()
        ->hasAttached($captain, ['captain' => true])
        ->hasAttached(User::factory()->count(3))
        ->hasAttached($player)
        ->for($tournament)
        ->createQuietly(["registration_state" => Team::PENDING]);

    $this->actingAs($captain)->post('/api/teams/' . $team->id . '/removePlayer/' . $player->id);

    $this->assertDatabaseHas("teams", [
        "id" => $team->id,
        "registration_state" => Team::NOT_FULL,
    ]);
});

