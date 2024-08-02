<?php

use App\Models\Rule;

it('can create the rules if none already exists', function () {
    $this->get('/api/rules')
    ->assertOk()
    ->assertJson([
        "data" => [
            "id" => 1
        ]
    ]);
});

it('can display rules', function () {
    $rule = Rule::factory()->create([
        "content" => "Lorem ipsum !"
    ]);

    $this->get('/api/rules')
    ->assertOk()
    ->assertJson([
        "data" => [
            "content" => $rule->content
        ]
    ]);
});
