<?php
use App\Models\PurchasedPlace;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\TournamentPrice;
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

    $tournament = Tournament::factory()->create([
        'type' => 'solo',
        'status' => 'open',
    ]);

    $tournamentPrice = TournamentPrice::factory()->create([
        'tournament_id' => $tournament->id,
    ]);

    // Register player in tournament
    $this->actingAs($user)->post('/api/tournaments/' . $tournament->id . '/register/' . $user->id);

    $this->assertDatabaseHas("purchased_places", [
        "user_id" => $user->id,
        'tournament_price_id' => $tournamentPrice->id,
        "paid" => false,
    ]);

    $this->actingAs($user)->post("api/purchasedPlaces/register/" . $user->id . "/" . $tournament->id)
        ->assertCreated()
        ->assertJson([
            "status" => "created",
            "message" => __("responses.purchasedPlaces.registered"),
            "data" => [
                "user_id" => $user->id,
                "tournament_price_id" => $tournamentPrice->id,
            ],
        ]);

    $this->assertDatabaseHas("purchased_places", [
        "user_id" => $user->id,
        'tournament_price_id' => $tournamentPrice->id,
        "paid" => true,
    ]);
});

it("updates the purchased place on team tournament", function () {
    $user = User::factory()->create();

    $tournament = Tournament::factory()->create([
        'type' => 'team',
        'status' => 'open',
    ]);

    $tournamentPrice = TournamentPrice::factory()->create([
        'tournament_id' => $tournament->id,
    ]);

    $team = Team::factory()
        ->hasAttached(User::factory()->create(), ['captain' => true])
        ->hasAttached(User::factory()->count(2))
        ->for($tournament)
        ->create();

    // Register player to the team
    $this->actingAs($user)->post('/api/teams/' . $team->id . '/addPlayer/' . $user->id);

    $this->assertDatabaseHas("purchased_places", [
        "user_id" => $user->id,
        'tournament_price_id' => $tournamentPrice->id,
        "paid" => false,
    ]);

    $this->actingAs($user)->post("api/purchasedPlaces/register/" . $user->id . "/" . $tournament->id)
        ->assertCreated()
        ->assertJson([
            "status" => "created",
            "message" => __("responses.purchasedPlaces.registered"),
            "data" => [
                "user_id" => $user->id,
                "tournament_price_id" => $tournamentPrice->id,
            ],
        ]);

    $this->assertDatabaseHas("purchased_places", [
        "user_id" => $user->id,
        'tournament_price_id' => $tournamentPrice->id,
        "paid" => true,
    ]);
});
