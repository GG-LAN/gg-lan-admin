<?php
use App\ApiCode;
use App\Models\User;
use App\Models\Tournament;
use App\Models\PurchasedPlace;
use App\Models\TournamentPrice;

it("can_get_purchased_places", function () {
    $user = User::factory()->create();

    $purchasedPlaces = PurchasedPlace::factory(10)->create();

    $this->actingAs($user)->get("api/purchasedPlaces")
    ->assertOk()
    ->assertJson([
        "data" => $purchasedPlaces->toArray()
    ]);
});

it("can_get_purchased_place", function () {
    $user = User::factory()->create();

    $purchasedPlace = PurchasedPlace::factory()->create();

    $this->actingAs($user)->get("api/purchasedPlaces/" . $purchasedPlace->id)
    ->assertOk()
    ->assertJson([
        "data" => $purchasedPlace->toArray()
    ]);
});

it("can_cant_register_purchase_place_as_another_user", function () {
    $user = User::factory()->create();
    $anotherUser = User::factory()->create();

    $tournament = Tournament::factory()->create();

    $this->actingAs($anotherUser)->post("api/purchasedPlaces/register/" . $user->id . "/" . $tournament->id)
    ->assertForbidden()
    ->assertJson([
        "status" => "error",
        "message" => __("responses.purchasedPlaces.cant_register")
    ]);
});

it("can_register_purchase_place", function () {
    $user = User::factory()->create();

    $tournament = Tournament::factory()->create();

    $tournamentPrice = TournamentPrice::factory()->create([
        'tournament_id' => $tournament->id
    ]);

    $this->actingAs($user)->post("api/purchasedPlaces/register/" . $user->id . "/" . $tournament->id)
    ->assertCreated()
    ->assertJson([
        "status" => "created",
        "message" => __("responses.purchasedPlaces.registered"),
        "data" => [
            "user_id" => $user->id,
            "tournament_price_id" => $tournamentPrice->id
        ]
    ]);

    $this->assertDatabaseHas("purchased_places", [
        "user_id" => $user->id,
        'tournament_price_id' => $tournamentPrice->id
    ]);
});

it("can_cant_register_purchase_place_if_already_purchased", function () {
    $user = User::factory()->create();

    $tournament = Tournament::factory()->create();

    $tournamentPrice = TournamentPrice::factory()->create([
        'tournament_id' => $tournament->id
    ]);

    // Registering
    $this->actingAs($user)->post("api/purchasedPlaces/register/" . $user->id . "/" . $tournament->id);
    
    // Double register
    $this->actingAs($user)->post("api/purchasedPlaces/register/" . $user->id . "/" . $tournament->id)
    ->assertForbidden()
    ->assertJson([
        "status" => "error",
        "message" => __("responses.purchasedPlaces.already_registered")
    ]);

    $this->assertDatabaseHas("purchased_places", [
        "user_id" => $user->id,
        'tournament_price_id' => $tournamentPrice->id
    ]);
});
