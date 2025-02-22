<?php

use App\Models\PurchasedPlace;
use App\Models\Tournament;
use App\Models\User;

beforeEach(function () {
    $this->user       = User::factory()->create();
    $this->tournament = Tournament::factory()->create();
});

test("Registering a place with paid @ true register the info in the database", function () {
    // The user has purchased a place
    $purchasedPlace = PurchasedPlace::register($this->user, $this->tournament, true);

    $this->assertDatabaseHas("purchased_places", [
        "id"                  => $purchasedPlace->id,
        "user_id"             => $this->user->id,
        "tournament_id"       => $this->tournament->id,
        "tournament_price_id" => $this->tournament->currentPrice()->id,
        "paid"                => true,
    ]);
});

test("Registering a place with paid @ false register the info in the database", function () {
    // The user has purchased a place
    $purchasedPlace = PurchasedPlace::register($this->user, $this->tournament);

    $this->assertDatabaseHas("purchased_places", [
        "id"                  => $purchasedPlace->id,
        "user_id"             => $this->user->id,
        "tournament_id"       => $this->tournament->id,
        "tournament_price_id" => null,
        "paid"                => false,
    ]);
});

test("Registering a place for a user that already have a purchased place with paid @ true doesn't reset the paid value", function () {
    // The user has purchased a place
    PurchasedPlace::register($this->user, $this->tournament, true);

    // New register for the same user
    $purchasedPlace = PurchasedPlace::register($this->user, $this->tournament);

    $this->assertDatabaseHas("purchased_places", [
        "id"                  => $purchasedPlace->id,
        "user_id"             => $this->user->id,
        "tournament_id"       => $this->tournament->id,
        "tournament_price_id" => $this->tournament->currentPrice()->id,
        "paid"                => true,
    ]);
});

test("Registering a place for a user that already have a purchased place with paid @ false doesn't change the paid value", function () {
    // The user has purchased a place
    PurchasedPlace::register($this->user, $this->tournament);

    // New register for the same user
    $purchasedPlace = PurchasedPlace::register($this->user, $this->tournament);

    $this->assertDatabaseHas("purchased_places", [
        "id"                  => $purchasedPlace->id,
        "user_id"             => $this->user->id,
        "tournament_id"       => $this->tournament->id,
        "tournament_price_id" => null,
        "paid"                => false,
    ]);
});
