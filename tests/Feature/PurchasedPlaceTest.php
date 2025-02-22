<?php
use App\Models\PurchasedPlace;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;

it("can_get_purchased_places", function () {
    $user = User::factory()->create();

    $purchasedPlaces = PurchasedPlace::factory(10)->create();

    $this->actingAs($user)->get("api/purchasedPlaces")
        ->assertOk()
        ->assertJson([
            "data" => $purchasedPlaces->toArray(),
        ]);
});

it("can_get_purchased_place", function () {
    $user = User::factory()->create();

    $purchasedPlace = PurchasedPlace::factory()->create();

    $this->actingAs($user)->get("api/purchasedPlaces/" . $purchasedPlace->id)
        ->assertOk()
        ->assertJson([
            "data" => $purchasedPlace->toArray(),
        ]);
});

it("updates the purchased place on solo tournament", function () {
    $user = User::factory()->create();

    $tournament = Tournament::factory()
        ->create([
            'type'   => 'solo',
            'status' => 'open',
        ]);

    // Register player in tournament
    $this->actingAs($user)->post('/api/tournaments/' . $tournament->id . '/register/' . $user->id);

    $this->assertDatabaseHas("purchased_places", [
        "user_id"             => $user->id,
        "tournament_id"       => $tournament->id,
        'tournament_price_id' => null,
        "paid"                => false,
    ]);

    $this->actingAs($user)->post("api/purchasedPlaces/register/" . $user->id . "/" . $tournament->id)
        ->assertCreated()
        ->assertJson([
            "status"  => "created",
            "message" => __("responses.purchasedPlaces.registered"),
            "data"    => [
                "user_id"             => $user->id,
                "tournament_id"       => $tournament->id,
                "tournament_price_id" => $tournament->currentPrice()->id,
            ],
        ]);

    $this->assertDatabaseHas("purchased_places", [
        "user_id"             => $user->id,
        "tournament_id"       => $tournament->id,
        'tournament_price_id' => $tournament->currentPrice()->id,
        "paid"                => true,
    ]);
});

it("updates the purchased place on team tournament", function () {
    $user = User::factory()->create();

    $tournament = Tournament::factory()
        ->create([
            'type'   => 'team',
            'status' => 'open',
        ]);

    $team = Team::factory()
        ->hasAttached(User::factory()->create(), ['captain' => true])
        ->hasAttached(User::factory()->count(2))
        ->for($tournament)
        ->create();

    // Register player to the team
    $this->actingAs($user)->post('/api/teams/' . $team->id . '/addPlayer/' . $user->id);

    $this->assertDatabaseHas("purchased_places", [
        "user_id"             => $user->id,
        "tournament_id"       => $tournament->id,
        'tournament_price_id' => null,
        "paid"                => false,
    ]);

    $this->actingAs($user)->post("api/purchasedPlaces/register/" . $user->id . "/" . $tournament->id)
        ->assertCreated()
        ->assertJson([
            "status"  => "created",
            "message" => __("responses.purchasedPlaces.registered"),
            "data"    => [
                "user_id"             => $user->id,
                "tournament_id"       => $tournament->id,
                "tournament_price_id" => $tournament->currentPrice()->id,
            ],
        ]);

    $this->assertDatabaseHas("purchased_places", [
        "user_id"             => $user->id,
        "tournament_id"       => $tournament->id,
        'tournament_price_id' => $tournament->currentPrice()->id,
        "paid"                => true,
    ]);
});

test("A purchased place is created for the captain after creating a team", function () {
    $tournament = Tournament::factory()
        ->createQuietly([
            'type'   => 'team',
            'status' => 'open',
            'places' => 4,
        ]);

    $data = [
        "name"          => "Mangemort",
        "description"   => "On roule sur la concu",
        'tournament_id' => $tournament->id,
    ];

    $user = User::factory()->createQuietly();

    $this->actingAs($user)->post('/api/teams/create', $data);

    $this->assertDatabaseHas("purchased_places", [
        "user_id"             => $user->id,
        "tournament_id"       => $tournament->id,
        'tournament_price_id' => null,
        "paid"                => false,
    ]);
});

test("A purchased place is created for the player that has been added to a team", function () {
    $tournament = Tournament::factory()
        ->createQuietly([
            'type'   => 'team',
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
        "user_id"             => $player->id,
        "tournament_id"       => $tournament->id,
        'tournament_price_id' => null,
        "paid"                => false,
    ]);
});

test("Purchased place is delete for the player that has been removed from a team", function () {
    $tournament = Tournament::factory()
        ->createQuietly([
            'type'   => 'team',
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
        "user_id"       => $player->id,
        "tournament_id" => $tournament->id,
    ]);
});

test("A purchased place is created after registering a player to a tournament", function () {
    $tournament = Tournament::factory()
        ->create([
            'type'   => 'solo',
            'status' => 'open',
            'places' => 4,
        ]);

    $user = User::factory()->create();

    $this->actingAs($user)->post('/api/tournaments/' . $tournament->id . '/register/' . $user->id);

    $this->assertDatabaseHas("purchased_places", [
        "user_id"             => $user->id,
        "tournament_id"       => $tournament->id,
        'tournament_price_id' => null,
        "paid"                => false,
    ]);
});

test("Purchased place is deleted after unregistering a player from a tournament", function () {
    $tournament = Tournament::factory()
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
