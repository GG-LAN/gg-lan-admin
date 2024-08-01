<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

it("can register player", function () {
    $data = [
        'name' => "Jean-Jacques Margoulin",
        'pseudo' => "Xx_MinecraftBoy_xX",
        'birth_date' => '1999-01-01',
        'email' => "test@example.com",
        'password' => 'secret',
        'password_confirmation' => 'secret'
    ];

    $data_result = [
        'pseudo' => "Xx_MinecraftBoy_xX"
    ];

    $this->post('/api/register', $data)
    ->assertCreated()
    ->assertJson([
        "data" => $data_result
    ]);

    $this->assertDatabaseHas('users', [
        'name' => "Jean-Jacques Margoulin",
        'pseudo' => "Xx_MinecraftBoy_xX",
        'birth_date' => '1999-01-01',
        'email' => "test@example.com"
    ]);

});

it("can't register a player with missing field", function () {
    $data = [
        'name' => "Jean-Jacques Margoulin",
        'birth_date' => '1999-01-01',
        'email' => "test@example.com",
        'password' => 'secret',
        'password_confirmation' => 'secret'
    ];

    $this->post('/api/register', $data)
    ->assertUnprocessable()
    ->assertJson([
        "status" => "error",
        "message" => __("responses.validation.error"),
        "data" => [
            "pseudo" => [
                __("validation.required", ["attribute" => "pseudo"])
            ]
        ]
    ]);
});


it("can login a player", function () {
    $user = User::factory()->create([
        'name' => "Test",
        'pseudo' => "Test pseudo",
        'birth_date' => '1999-01-01',
        'email' => "test@test.com",
        'password' => bcrypt("secret"),
    ]);

    $credentials = [
        "email" => "test@test.com",
        "password" => "secret"
    ];

    $this->post("/api/login", $credentials)
    ->assertOk()
    ->assertJson([
        "status" => "success"
    ]);
});

it("can't login a player with missing field", function () {
    $credentials = [
        "email" => "test@test.com",
    ];

    $this->post("/api/login", $credentials)
    ->assertUnprocessable()
    ->assertJson([
        "status" => "error",
        "message" => __("responses.validation.error"),
        "data" => [
            "password" => [
                __("validation.required", ["attribute" => "password"])
            ]
        ]
    ]);
});

it("can't login a player if it doesn't exists", function () {
    $credentials = [
        "email" => "test@test.com",
        "password" => "secret",
    ];

    $this->post("/api/login", $credentials)
    ->assertUnauthorized()
    ->assertJson([
        "status" => "error",
        "message" => __("responses.register.wrong_match"),
    ]);
});


it("can logout a player", function () {
    $user = User::factory()->create([
        'name' => "Test",
        'pseudo' => "Test pseudo",
        'birth_date' => '1999-01-01',
        'email' => "test@test.com",
        'password' => bcrypt("secret"),
    ]);

    Sanctum::actingAs($user, ['*']);

    $this->actingAs($user)->post("/api/logout")
    ->assertOk()
    ->assertJson([
        "status" => "success",
        "message" => __("responses.register.logout"),
    ]);
});

it("can't logout a player if not authenticated", function () {
    $this->post("/api/logout")
    ->assertUnauthorized()
    ->assertJson([
        "status"  => "error",
        "message" => __("responses.unauthenticated")
    ]);
});
