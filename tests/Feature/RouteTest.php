<?php
use App\Models\User;
use function Spatie\RouteTesting\routeTesting;

routeTesting("redirect to login page if not authenticated")
    ->include("/")
    ->assertRedirect("/login");

describe('redirected and logged out if not admin', function () {
    beforeEach(function () {
        $this->actingAs(User::factory()->create([
            "admin" => false,
        ]));
    });

    routeTesting('route')
        ->include("/", "players", "teams", "tournaments", "payments", "games", "faqs", "logs", "settings")
        ->assertRedirect();
});

describe('all index routes', function () {
    beforeEach(function () {
        $this->actingAs(User::factory()->create([
            "admin" => true,
        ]));
    });

    routeTesting('route')
        ->include("/", "players", "teams", "tournaments", "payments", "games", "faqs", "logs", "settings")
        ->assertSuccessful();
});
