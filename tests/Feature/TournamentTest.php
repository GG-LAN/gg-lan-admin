<?php

use App\Models\Game;
use App\Models\PurchasedPlace;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\TournamentPrice;
use App\Models\User;

it("can_get_tournaments", function () {
    $tournaments = Tournament::factory(10)->create();

    $this->get('/api/tournaments')
        ->assertOk()
        ->assertJson([
            "data" => $tournaments->toArray(),
        ]);
});

it("can_get_tournaments_with_pagination", function () {
    Tournament::factory(10)->create();

    $item_per_page = 5;
    $tournaments   = Tournament::paginate($item_per_page);

    $response = $this->get('/api/tournaments/paginate/' . $item_per_page);

    $result = json_decode($response->getContent(), true);

    $response->assertOk();

    $this->assertEquals($item_per_page, count($result['data']['data']));
});

it("can_get_tournament", function () {
    $tournament = Tournament::factory()->create();

    $this->get('/api/tournaments/' . $tournament->id)
        ->assertOk()
        ->assertJson([
            "data" => $tournament->toArray(),
        ]);
});

it("can_add_player_to_tournament_of_type_solo", function () {
    $tournament = Tournament::factory()
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name'          => $tournament->name,
                'tournament_id' => $tournament->id,
                'type'          => 'normal',
                'active'        => true,
            ];
        }), "prices")
        ->create([
            'type'   => 'solo',
            'status' => 'open',
        ]);

    $user = User::factory()->create();

    $this->actingAs($user)->post('/api/tournaments/' . $tournament->id . '/register/' . $user->id)
        ->assertOk()
        ->assertJson([
            'status'  => "success",
            'message' => __("responses.tournament.registered"),
        ]);
});

it("can_add_player_to_tournament_of_type_solo_as_admin", function () {
    $tournament = Tournament::factory()
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name'          => $tournament->name,
                'tournament_id' => $tournament->id,
                'type'          => 'normal',
                'active'        => true,
            ];
        }), "prices")
        ->create([
            'type'   => 'solo',
            'status' => 'open',
        ]);

    $user  = User::factory()->create();
    $admin = User::factory()->create([
        'admin' => 1,
    ]);

    $this->actingAs($admin)->post('/api/tournaments/' . $tournament->id . '/register/' . $user->id)
        ->assertOk()
        ->assertJson([
            'status'  => "success",
            'message' => __("responses.tournament.registered"),
        ]);
});

it("cant_add_another_player_to_tournament_of_type_solo", function () {
    $tournament = Tournament::factory()
        ->create([
            'type'   => 'solo',
            'status' => 'open',
        ]);

    $user       = User::factory()->create();
    $other_user = User::factory()->create();

    $this->actingAs($user)->post('/api/tournaments/' . $tournament->id . '/register/' . $other_user->id)
        ->assertForbidden()
        ->assertJson([
            'status'  => "error",
            'message' => __("responses.tournament.player_not_you"),
        ]);
});

it("can_remove_player_from_tournament_of_type_solo", function () {
    $tournament = Tournament::factory()
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name'          => $tournament->name,
                'tournament_id' => $tournament->id,
                'type'          => 'normal',
                'active'        => true,
            ];
        }), "prices")
        ->create([
            'type'   => 'solo',
            'status' => 'open',
        ]);

    $user = User::factory()->create();

    $tournament->players()->attach($user);

    $this->actingAs($user)->post('/api/tournaments/' . $tournament->id . '/unregister/' . $user->id)
        ->assertOk()
        ->assertJson([
            'status'  => "success",
            'message' => __("responses.tournament.unregistered"),
        ]);
});

it("cant_remove_another_player_from_tournament_of_type_solo", function () {
    $tournament = Tournament::factory()
        ->create([
            'type'   => 'solo',
            'status' => 'open',
        ]);

    $user       = User::factory()->create();
    $other_user = User::factory()->create();

    $this->actingAs($user)->post('/api/tournaments/' . $tournament->id . '/unregister/' . $other_user->id)
        ->assertForbidden()
        ->assertJson([
            'status'  => "error",
            'message' => __("responses.tournament.player_not_you"),
        ]);
});

it("can_remove_player_from_tournament_of_type_solo_as_admin", function () {
    $tournament = Tournament::factory()
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name'          => $tournament->name,
                'tournament_id' => $tournament->id,
                'type'          => 'normal',
                'active'        => true,
            ];
        }), "prices")
        ->create([
            'type'   => 'solo',
            'status' => 'open',
        ]);

    $user = User::factory()->create();

    $admin = User::factory()->create([
        'admin' => 1,
    ]);

    $tournament->players()->attach($user);

    $this->actingAs($admin)->post('/api/tournaments/' . $tournament->id . '/unregister/' . $user->id)
        ->assertOk()
        ->assertJson([
            'status'  => "success",
            'message' => __("responses.tournament.unregistered"),
        ]);
});

it("get_tournament_purchased_places", function () {
    $user = User::factory()->create();

    $tournament = Tournament::factory()->create();

    $tournamentPrice = TournamentPrice::factory()->create([
        'tournament_id' => $tournament->id,
    ]);

    $purchasedPlaces = PurchasedPlace::factory(4)->create([
        'tournament_price_id' => $tournamentPrice->id,
    ]);

    $this->actingAs($user)->get("api/tournaments/" . $tournament->id . "/purchasedPlaces")
        ->assertOk()
        ->assertJson([
            "data" => $purchasedPlaces->toArray(),
        ]);
});

it("cant_register_player_if_tournament_is_full", function () {
    $tournament = Tournament::factory()->create([
        'type'   => 'solo',
        'status' => 'open',
        'places' => 4,
    ]);

    $players = User::factory(4)->create();
    foreach ($players as $player) {
        $tournament->players()->attach($player);
    }

    $user = User::factory()->create();

    $this->actingAs($user)->post('/api/tournaments/' . $tournament->id . '/register/' . $user->id)
        ->assertForbidden()
        ->assertJson([
            'status'  => "error",
            'message' => __("responses.tournament.full"),
        ]);
});

it("cant_register_player_to_a_team_tournament", function () {
    $tournament = Tournament::factory()->create([
        'type'   => 'team',
        'status' => 'open',
        'places' => 4,
    ]);

    $user = User::factory()->create();

    $this->actingAs($user)->post('/api/tournaments/' . $tournament->id . '/register/' . $user->id)
        ->assertForbidden()
        ->assertJson([
            'status'  => "error",
            'message' => __("responses.tournament.not_solo"),
        ]);
});

it("cant_register_twice_player_to_same_tournament", function () {
    $tournament = Tournament::factory()->create([
        'type'   => 'solo',
        'status' => 'open',
        'places' => 4,
    ]);

    $user = User::factory()->create();

    // First register action (valid)
    $tournament->players()->attach($user);

    // Second register
    $this->actingAs($user)->post('/api/tournaments/' . $tournament->id . '/register/' . $user->id)
        ->assertForbidden()
        ->assertJson([
            'status'  => "error",
            'message' => __("responses.tournament.already_registered"),
        ]);
});

it("cant_unregister_twice_player_to_same_tournament", function () {
    $tournament = Tournament::factory()->create([
        'type'   => 'solo',
        'status' => 'open',
        'places' => 4,
    ]);

    $user = User::factory()->create();

    // Register action (valid)
    $tournament->players()->attach($user);

    // First unregister (valid)
    $this->actingAs($user)->post('/api/tournaments/' . $tournament->id . '/unregister/' . $user->id);

    // Second register (invalid)
    $this->actingAs($user)->post('/api/tournaments/' . $tournament->id . '/unregister/' . $user->id)
        ->assertForbidden()
        ->assertJson([
            'status'  => "error",
            'message' => __("responses.tournament.not_registered"),
        ]);
});

test("A purchased place is created after registering a player to a tournament", function () {
    $tournament = Tournament::factory()
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name'          => $tournament->name,
                'tournament_id' => $tournament->id,
                'type'          => 'normal',
                'active'        => true,
            ];
        }), "prices")
        ->create([
            'type'   => 'solo',
            'status' => 'open',
            'places' => 4,
        ]);

    $user = User::factory()->create();

    $this->actingAs($user)->post('/api/tournaments/' . $tournament->id . '/register/' . $user->id);

    $this->assertDatabaseHas("purchased_places", [
        "user_id"             => $user->id,
        'tournament_price_id' => $tournament->currentPrice()->id,
        "paid"                => false,
    ]);
});

test("Purchased place is deleted after unregistering a player from a tournament", function () {
    $tournament = Tournament::factory()
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name'          => $tournament->name,
                'tournament_id' => $tournament->id,
                'type'          => 'normal',
                'active'        => true,
            ];
        }), "prices")
        ->create([
            'type'   => 'solo',
            'status' => 'open',
            'places' => 4,
        ]);

    $user = User::factory()->create();

    $tournament->players()->attach($user);

    $this->actingAs($user)->post('/api/tournaments/' . $tournament->id . '/unregister/' . $user->id);

    $this->assertDatabaseMissing("purchased_places", [
        "user_id"             => $user->id,
        'tournament_price_id' => $tournament->currentPrice()->id,
    ]);
});

it("can list all unregistered teams for a open tournament", function () {
    $game = Game::factory()->create([
        "name"   => "CS:GO",
        "places" => 5,
    ]);

    $tournament = Tournament::factory()
        ->for($game)
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name'          => $tournament->name,
                'tournament_id' => $tournament->id,
                'type'          => 'normal',
                'active'        => true,
            ];
        }), "prices")
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name'          => $tournament->name . " Last Week",
                'tournament_id' => $tournament->id,
                'type'          => 'last_week',
                'price_id'      => "price_1OtYO6AOwlBXXotY6zNeBkVJ",
                'price'         => "35,00 €",
                'active'        => false,
            ];
        }), "prices")
        ->create(["status" => "open", "type" => "team"]);

    $teams_full = Team::factory(4)
        ->for($tournament)
        ->hasAttached(User::factory(), ["captain" => true])
        ->has(User::factory(4))
        ->create([
            "registration_state" => Team::REGISTERED,
        ]);

    $teams_not_full = Team::factory(4)
        ->for($tournament)
        ->hasAttached(User::factory(), ["captain" => true])
        ->has(User::factory(2))
        ->create([
            "registration_state" => Team::NOT_FULL,
        ]);

    $this->get("/api/tournaments/{$tournament->id}/available-teams")
        ->assertOk()
        ->assertJson([
            "status"  => "success",
            "message" => "",
            "data"    => $teams_not_full->toArray(),
        ]);
});

it("don't list unregistered teams of another tournament for a open tournament", function () {
    $user = User::factory()->create();

    $gameCSGO = Game::factory()->create([
        "name"   => "CS:GO",
        "places" => 5,
    ]);

    $gameTRACKMANIA = Game::factory()->create([
        "name"   => "Trackmania",
        "places" => 3,
    ]);

    $tournamentCSGO = Tournament::factory()
        ->for($gameCSGO)
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name'          => $tournament->name,
                'tournament_id' => $tournament->id,
                'type'          => 'normal',
                'active'        => true,
            ];
        }), "prices")
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name'          => $tournament->name . " Last Week",
                'tournament_id' => $tournament->id,
                'type'          => 'last_week',
                'price_id'      => "price_1OtYO6AOwlBXXotY6zNeBkVJ",
                'price'         => "35,00 €",
                'active'        => false,
            ];
        }), "prices")
        ->create(["status" => "open", "type" => "team"]);

    $tournamentTrackmania = Tournament::factory()
        ->for($gameTRACKMANIA)
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name'          => $tournament->name,
                'tournament_id' => $tournament->id,
                'type'          => 'normal',
                'active'        => true,
            ];
        }), "prices")
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name'          => $tournament->name . " Last Week",
                'tournament_id' => $tournament->id,
                'type'          => 'last_week',
                'price_id'      => "price_1OtYO6AOwlBXXotY6zNeBkVJ",
                'price'         => "35,00 €",
                'active'        => false,
            ];
        }), "prices")
        ->create(["status" => "open", "type" => "team"]);

    $teams_full_csgo = Team::factory(4)
        ->for($tournamentCSGO)
        ->hasAttached(User::factory(), ["captain" => true])
        ->has(User::factory(4))
        ->create([
            "registration_state" => Team::REGISTERED,
        ]);

    $teams_not_full_csgo = Team::factory(4)
        ->for($tournamentCSGO)
        ->hasAttached(User::factory(), ["captain" => true])
        ->has(User::factory(2))
        ->create([
            "registration_state" => Team::NOT_FULL,
        ]);

    $teams_full_trackmania = Team::factory(4)
        ->for($tournamentTrackmania)
        ->hasAttached(User::factory(), ["captain" => true])
        ->create([
            "registration_state" => Team::REGISTERED,
        ]);

    $teams_not_full_trackmania = Team::factory(4)
        ->for($tournamentTrackmania)
        ->hasAttached(User::factory(), ["captain" => true])
        ->has(User::factory(1))
        ->create([
            "registration_state" => Team::NOT_FULL,
        ]);

    $this->get("/api/tournaments/{$tournamentCSGO->id}/available-teams")
        ->assertOk()
        ->assertJson([
            "status"  => "success",
            "message" => "",
            "data"    => $teams_not_full_csgo->toArray(),
        ]);
});

it("don't list unregistered teams if it's not a team tournament", function () {
    $game = Game::factory()->create([
        "name"   => "Smash Bros",
        "places" => 1,
    ]);

    $tournament = Tournament::factory()
        ->for($game)
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name'          => $tournament->name,
                'tournament_id' => $tournament->id,
                'type'          => 'normal',
                'active'        => true,
            ];
        }), "prices")
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name'          => $tournament->name . " Last Week",
                'tournament_id' => $tournament->id,
                'type'          => 'last_week',
                'price_id'      => "price_1OtYO6AOwlBXXotY6zNeBkVJ",
                'price'         => "35,00 €",
                'active'        => false,
            ];
        }), "prices")
        ->create(["status" => "open", "type" => "solo"]);

    $this->get("/api/tournaments/{$tournament->id}/available-teams")
        ->assertForbidden()
        ->assertJson([
            "status"  => "error",
            "message" => __("responses.tournament.not_team_tournament"),
            "data"    => [],
        ]);
});

it("don't list unregistered teams if the tournament is closed/finished", function () {
    $game = Game::factory()->create([
        "name"   => "CS:GO",
        "places" => 5,
    ]);

    $tournament = Tournament::factory()
        ->for($game)
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name'          => $tournament->name,
                'tournament_id' => $tournament->id,
                'type'          => 'normal',
                'active'        => true,
            ];
        }), "prices")
        ->has(TournamentPrice::factory()->state(function (array $attributes, Tournament $tournament) {
            return [
                'name'          => $tournament->name . " Last Week",
                'tournament_id' => $tournament->id,
                'type'          => 'last_week',
                'price_id'      => "price_1OtYO6AOwlBXXotY6zNeBkVJ",
                'price'         => "35,00 €",
                'active'        => false,
            ];
        }), "prices")
        ->create(["status" => "closed"]);

    $this->get("/api/tournaments/{$tournament->id}/available-teams")
        ->assertForbidden()
        ->assertJson([
            "status"  => "error",
            "message" => __("responses.tournament.not_exists"),
            "data"    => [],
        ]);

});
