<?php

use App\Models\Game;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\TournamentPrice;
use App\Models\User;

it("can get players", function () {
    $users = User::factory(10)->createQuietly();

    $users = User::without("purchasedPlaces")->get(['id', 'pseudo', 'image', 'created_at', 'updated_at']);

    $this->get('/api/players')
        ->assertOk()
        ->assertJson([
            "data" => $users->toArray(),
        ]);
});

it("can get players with pagination", function () {
    User::factory(10)->createQuietly();

    $item_per_page = 5;
    $users = User::without("purchasedPlaces")->paginate($item_per_page, ['id', 'pseudo', 'image', 'created_at', 'updated_at']);

    $response = $this->get('/api/players/paginate/' . $item_per_page);

    $result = json_decode($response->getContent(), true);

    $response->assertOk();

    $this->assertEquals($item_per_page, count($result['data']['data']));
});

it("can get a player", function () {
    $user = User::factory()->createQuietly();

    $response = $this->get('/api/players/' . $user->id)
        ->assertOk()
        ->assertJson([
            "data" => $user->without("purchasedPlaces")->first(['id', 'pseudo', 'image'])->toArray(),
        ]);
});

it("can update a player", function () {
    $user = User::factory()->createQuietly();

    $updated_values = [
        'pseudo' => "Updated pseudo",
        'birth_date' => '1999-01-02',
    ];

    $this->actingAs($user)->put('/api/players/' . $user->id, $updated_values)
        ->assertOk()
        ->assertJson([
            "data" => [
                "pseudo" => "Updated pseudo",
            ],
        ]);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'pseudo' => $updated_values['pseudo'],
        'birth_date' => '1999-01-02',
    ]);
});

it("can't update another player", function () {
    $user = User::factory()->createQuietly();
    $user1 = User::factory()->createQuietly();

    $updated_values = [
        'pseudo' => "Updated pseudo",
        'birth_date' => '1999-01-02',
    ];

    $this->actingAs($user1)->put('/api/players/' . $user->id, $updated_values)
        ->assertForbidden()
        ->assertJson([
            'status' => "error",
            'message' => __("responses.users.cant_update"),
        ]);
});

it("can delete a player as admin", function () {
    $admin = User::factory()->createQuietly([
        'admin' => 1,
    ]);

    $user = User::factory()->createQuietly();

    $this->actingAs($admin)->delete('/api/players/' . $user->id)
        ->assertOk()
        ->assertJson([
            "status" => "success",
            "message" => __("responses.users.deleted"),
        ]);
});

it("can't delete a player as normal user", function () {
    $user = User::factory()->createQuietly();

    $user1 = User::factory()->createQuietly();

    $this->actingAs($user1)->delete('/api/players/' . $user->id)
        ->assertForbidden()
        ->assertJson([
            'status' => "error",
            'message' => __("responses.users.only_admin"),
        ]);
});

it("can get player's tournament with one solo tournament", function () {
    $user = User::factory()->createQuietly();

    $tournament = Tournament::factory()->createQuietly([
        'type' => 'solo',
        'status' => 'open',
    ]);

    $tournament->players()->attach($user);

    $this->actingAs($user)->get('/api/players/' . $user->id . '/tournaments')
        ->assertOk()
        ->assertJson([
            "data" => [
                $tournament->toArray(),
            ],
        ]);
});

it("can get player's tournament with multiple solo tournaments", function () {
    $user = User::factory()->createQuietly();

    $tournamentOpen = Tournament::factory()->createQuietly([
        'type' => 'solo',
        'status' => 'open',
    ]);
    $tournamentClosed = Tournament::factory()->createQuietly([
        'type' => 'solo',
        'status' => 'closed',
    ]);
    $tournamentFinished = Tournament::factory()->createQuietly([
        'type' => 'solo',
        'status' => 'finished',
    ]);

    $tournamentOpen->players()->attach($user);
    $tournamentClosed->players()->attach($user);
    $tournamentFinished->players()->attach($user);

    $this->actingAs($user)->get('/api/players/' . $user->id . '/tournaments')
        ->assertOk()
        ->assertJson([
            "data" => [
                $tournamentOpen->toArray(),
                $tournamentClosed->toArray(),
                $tournamentFinished->toArray(),
            ],
        ]);
});

it("can get player's tournament with one team tournament", function () {
    $user = User::factory()->createQuietly();

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
        ]);

    $team = Team::factory()
        ->hasAttached(User::factory()->count(1), ['captain' => true])
        ->createQuietly(["tournament_id" => $tournament->id]);

    $team->users()->attach($user);

    $this->actingAs($user)->get('/api/players/' . $user->id . '/tournaments')
        ->assertOk()
        ->assertJson([
            "data" => [
                $tournament->toArray(),
            ],
        ]);
});

it("can get player's tournament with multiple team tournaments", function () {
    $user = User::factory()->createQuietly();

    $tournament1 = Tournament::factory()
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
        ]);

    $tournament2 = Tournament::factory()
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
        ]);

    $team1 = Team::factory()
        ->hasAttached(User::factory()->count(1), ['captain' => true])
        ->createQuietly(["tournament_id" => $tournament1->id]);
    $team1->users()->attach($user);

    $team2 = Team::factory()
        ->hasAttached(User::factory()->count(1), ['captain' => true])
        ->createQuietly(["tournament_id" => $tournament2->id]);
    $team2->users()->attach($user);

    $this->actingAs($user)->get('/api/players/' . $user->id . '/tournaments')
        ->assertOk()
        ->assertJson([
            "data" => [
                $tournament1->toArray(),
                $tournament2->toArray(),
            ],
        ]);
});

it("can get player's tournament with solo and team tournament", function () {
    $user = User::factory()->createQuietly();

    $tournamentTeam = Tournament::factory()
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
        ]);

    $team = Team::factory()
        ->hasAttached(User::factory()->count(1), ['captain' => true])
        ->createQuietly(["tournament_id" => $tournamentTeam->id]);
    $team->users()->attach($user);

    $tournamentSolo = Tournament::factory()
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
            'status' => 'open',
        ]);

    $tournamentSolo->players()->attach($user);

    $this->actingAs($user)->get('/api/players/' . $user->id . '/tournaments')
        ->assertOk()
        ->assertJson([
            "data" => [
                $tournamentSolo->toArray(),
                $tournamentTeam->toArray(),
            ],
        ]);
});

it("can get player's tournament return empty array if player has no tournaments associated", function () {
    $user = User::factory()->createQuietly();

    $this->actingAs($user)->get('/api/players/' . $user->id . '/tournaments')
        ->assertOk()
        ->assertJson([
            "data" => [],
        ]);
});

it("can get player's team with no team", function () {
    $user = User::factory()->createQuietly();

    $this->actingAs($user)->get('/api/players/' . $user->id . '/teams')
        ->assertOk()
        ->assertJson([
            "data" => [],
        ]);
});

it("can get player's team with one team", function () {
    $user = User::factory()->createQuietly();

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
        ]);

    $team = Team::factory()
        ->hasAttached(User::factory()->count(1), ['captain' => true])
        ->createQuietly(["tournament_id" => $tournament->id]);
    $team->users()->attach($user);

    $this->actingAs($user)->get('/api/players/' . $user->id . '/teams')
        ->assertOk()
        ->assertJson([
            "data" => [
                $team->toArray(),
            ],
        ]);
});

it("can get player's team with multiple teams", function () {
    $user = User::factory()->createQuietly();

    $tournament1 = Tournament::factory()
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
        ]);

    $tournament2 = Tournament::factory()
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
        ]);

    $team1 = Team::factory()
        ->hasAttached(User::factory()->count(1), ['captain' => true])
        ->createQuietly(["tournament_id" => $tournament1->id]);
    $team1->users()->attach($user);

    $team2 = Team::factory()
        ->hasAttached(User::factory()->count(1), ['captain' => true])
        ->createQuietly(["tournament_id" => $tournament2->id]);
    $team2->users()->attach($user);

    $this->actingAs($user)->get('/api/players/' . $user->id . '/teams')
        ->assertOk()
        ->assertJson([
            "data" => [
                $team1->toArray(),
                $team2->toArray(),
            ],
        ]);
});

it("can player leave a team", function () {
    $user = User::factory()->createQuietly();

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
        ]);

    $team = Team::factory()
        ->hasAttached(User::factory()->count(1), ['captain' => true])
        ->createQuietly(["tournament_id" => $tournament->id]);
    $team->users()->attach($user);

    $this->actingAs($user)->post('/api/players/' . $user->id . '/leaveTeam/' . $team->id)
        ->assertOk()
        ->assertJson([
            "message" => __("responses.users.team_left"),
        ]);
});

it("can't leave a team if player not in it", function () {
    $user = User::factory()->createQuietly();

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
        ]);

    $team = Team::factory()
        ->hasAttached(User::factory()->count(1), ['captain' => true])
        ->createQuietly(["tournament_id" => $tournament->id]);

    $this->actingAs($user)->post('/api/players/' . $user->id . '/leaveTeam/' . $team->id)
        ->assertForbidden()
        ->assertJson([
            "message" => __("responses.team.player_not_in_team"),
        ]);
});

it("can't leave a team if player is captain of it", function () {
    $user = User::factory()->createQuietly();

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
        ]);

    $team = Team::factory()
        ->hasAttached($user, ['captain' => true])
        ->createQuietly(["tournament_id" => $tournament->id]);

    $this->actingAs($user)->post('/api/players/' . $user->id . '/leaveTeam/' . $team->id)
        ->assertForbidden()
        ->assertJson([
            "message" => __("responses.team.captain_cant_leave"),
        ]);
});

it("can't leave a team if auth user is not the same as player", function () {
    $user = User::factory()->createQuietly();
    $user2 = User::factory()->createQuietly();

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
        ]);

    $team = Team::factory()
        ->hasAttached($user, ['captain' => true])
        ->createQuietly(["tournament_id" => $tournament->id]);
    $team->users()->attach($user);

    $this->actingAs($user2)->post('/api/players/' . $user->id . '/leaveTeam/' . $team->id)
        ->assertForbidden()
        ->assertJson([
            "message" => __("responses.users.player_not_you"),
        ]);
});

test("Leaving a team that is full and registered will also update his registration state", function () {
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

    $player = User::factory()->createQuietly();

    $team = Team::factory()
        ->hasAttached(User::factory()->createQuietly(), ['captain' => true])
        ->hasAttached(User::factory()->count(3))
        ->hasAttached($player)
        ->for($tournament)
        ->createQuietly(["registration_state" => Team::REGISTERED]);

    $this->actingAs($player)->post('/api/players/' . $player->id . '/leaveTeam/' . $team->id);

    $this->assertDatabaseHas("teams", [
        "id" => $team->id,
        "registration_state" => Team::NOT_FULL,
    ]);
});

test("Leaving a team that is full and pending will also update his registration state", function () {
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

    $player = User::factory()->createQuietly();

    $team = Team::factory()
        ->hasAttached(User::factory()->createQuietly(), ['captain' => true])
        ->hasAttached(User::factory()->count(3))
        ->hasAttached($player)
        ->for($tournament)
        ->createQuietly(["registration_state" => Team::PENDING]);

    $this->actingAs($player)->post('/api/players/' . $player->id . '/leaveTeam/' . $team->id);

    $this->assertDatabaseHas("teams", [
        "id" => $team->id,
        "registration_state" => Team::NOT_FULL,
    ]);
});
