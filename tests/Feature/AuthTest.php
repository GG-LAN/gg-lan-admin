<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

it("test_can_register", function () {
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

it("test_cant_register_with_missing_field", function () {
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


it("test_can_login", function () {
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

it("test_cant_login_with_missing_field", function () {
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

it("test_cant_login_if_user_dont_exists", function () {
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


it("test_can_logout", function () {
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

it("test_cant_logout_if_not_authenticated", function () {
    $this->post("/api/logout")
    ->assertUnauthorized()
    ->assertJson([
        "status"  => "error",
        "message" => __("responses.unauthenticated")
    ]);
});
