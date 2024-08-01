<?php

use App\ApiCode;
use App\Models\Game;
use App\Models\Team;
use App\Models\User;
use App\Models\Tournament;
use App\Models\PurchasedPlace;
use App\Models\TournamentPrice;
use Stripe\StripeClient as Stripe;
    
it("can_get_tournaments", function () {
    $tournaments = Tournament::factory(10)->create();

    $this->get('/api/tournaments')
    ->assertOk()
    ->assertJson([
        "data" => $tournaments->toArray()
    ]);
});

it("can_get_tournaments_with_pagination", function () {
    Tournament::factory(10)->create();

    $item_per_page = 5;
    $tournaments = Tournament::paginate($item_per_page);
    
    $response = $this->get('/api/tournaments/paginate/'.$item_per_page);

    $result = json_decode($response->getContent(), true);
    
    $response->assertOk();

    $this->assertEquals($item_per_page, count($result['data']['data']));
});

it("can_get_tournament", function () {
    $tournament = Tournament::factory()->create();

    $this->get('/api/tournaments/'.$tournament->id)
    ->assertOk()
    ->assertJson([
        "data" => $tournament->toArray()
    ]);
});

it("can_add_player_to_tournament_of_type_solo", function () {
    $tournament = Tournament::factory()->create([
        'type' => 'solo',
        'status' => 'open'
    ]);

    $user = User::factory()->create();

    $this->actingAs($user)->post('/api/tournaments/'.$tournament->id.'/register/'.$user->id)
    ->assertOk()
    ->assertJson([
        'status'  => "success",
        'message' => __("responses.tournaments.registered")
    ]);
});

it("can_add_player_to_tournament_of_type_solo_as_admin", function () {
    $tournament = Tournament::factory()->create([
        'type' => 'solo',
        'status' => 'open'
    ]);

    $user = User::factory()->create();
    $admin = User::factory()->create([
        'admin' => 1
    ]);

    $this->actingAs($admin)->post('/api/tournaments/'.$tournament->id.'/register/'.$user->id)
    ->assertOk()
    ->assertJson([
        'status'  => "success",
        'message' => __("responses.tournaments.registered")
    ]);
});

it("cant_add_another_player_to_tournament_of_type_solo", function () {
    $tournament = Tournament::factory()->create([
        'type' => 'solo',
        'status' => 'open'
    ]);

    $user = User::factory()->create();
    $other_user = User::factory()->create();

    $this->actingAs($user)->post('/api/tournaments/'.$tournament->id.'/register/'.$other_user->id)
    ->assertForbidden()
    ->assertJson([
        'status'  => "error",
        'message' => __("responses.tournaments.player_not_you")
    ]);
});

it("can_remove_player_from_tournament_of_type_solo", function () {
    $tournament = Tournament::factory()->create([
        'type' => 'solo',
        'status' => 'open'
    ]);

    $user = User::factory()->create();

    $tournament->players()->attach($user);

    $this->actingAs($user)->post('/api/tournaments/'.$tournament->id.'/unregister/'.$user->id)
    ->assertOk()
    ->assertJson([
        'status' => "success",
        'message' => __("responses.tournaments.unregistered")
    ]);
});

it("cant_remove_another_player_from_tournament_of_type_solo", function () {
    $tournament = Tournament::factory()->create([
        'type' => 'solo',
        'status' => 'open'
    ]);

    $user = User::factory()->create();
    $other_user = User::factory()->create();

    $this->actingAs($user)->post('/api/tournaments/'.$tournament->id.'/unregister/'.$other_user->id)
    ->assertForbidden()
    ->assertJson([
        'status' => "error",
        'message' => __("responses.tournaments.player_not_you")
    ]);
});

it("can_remove_player_from_tournament_of_type_solo_as_admin", function () {
    $tournament = Tournament::factory()->create([
        'type' => 'solo',
        'status' => 'open'
    ]);

    $user = User::factory()->create();

    $admin = User::factory()->create([
        'admin' => 1
    ]);

    $tournament->players()->attach($user);

    $this->actingAs($admin)->post('/api/tournaments/'.$tournament->id.'/unregister/'.$user->id)
    ->assertOk()
    ->assertJson([
        'status' => "success",
        'message' => __("responses.tournaments.unregistered")
    ]);
});

// it("create_tournament_also_create_stripe_price_in_db", function () {
//     $data = [
//         "name"                  => "GG-LAN#12 CS:GO",
//         "description"           => "Let's rock this LAN",
//         'game_id'               => strval(Game::factory()->create()->id),
//         'start_date'            => "2021-05-01",
//         'end_date'              => "2021-05-02",
//         "places"                => "20",
//         'cashprize'             => "296",
//         'type'                  => 'team',
//         'normal_place_price'    => 30,
//         'last_week_place_price' => 35
//     ];

//     $admin = User::factory()->create([
//         'admin' => 1
//     ]);
    
//     $this->actingAs($admin)->post('/api/tournaments', $data);

//     $this->assertDatabaseHas("tournament_prices", [
//         "tournament_id" => $response["id"],
//         "type" => "normal"
//     ]);

//     $this->assertDatabaseHas("tournament_prices", [
//         "tournament_id" => $response["id"],
//         "type" => "last_week"
//     ]);

//     // Archive created product.
//     $stripe = new Stripe(config("app.stripe_api_key"));
//     foreach ($stripe->products->all(["active" => true])->data as $product) {
//         if ($product->name != "GG-LAN #test") {
//             $stripe->products->update($product->id, ["active" => false]);
//         }
//     }
// });

it("get_tournament_purchased_places", function () {
    $user = User::factory()->create();

    $tournament = Tournament::factory()->create();

    $tournamentPrice = TournamentPrice::factory()->create([
        'tournament_id' => $tournament->id
    ]);

    $purchasedPlaces = PurchasedPlace::factory(4)->create([
        'tournament_price_id' => $tournamentPrice->id
    ]);
    
    $this->actingAs($user)->get("api/tournaments/" . $tournament->id . "/purchasedPlaces")
    ->assertOk()
    ->assertJson([
        "data" => $purchasedPlaces->toArray()
    ]);
});

it("cant_register_player_if_tournament_is_full", function () {
    $tournament = Tournament::factory()->create([
        'type' => 'solo',
        'status' => 'open',
        'places' => 4
    ]);
    
    $players = User::factory(4)->create();
    foreach ($players as $player) {
        $tournament->players()->attach($player);
    }

    $user = User::factory()->create();

    $this->actingAs($user)->post('/api/tournaments/'.$tournament->id.'/register/'.$user->id)
    ->assertForbidden()
    ->assertJson([
        'status'  => "error",
        'message' => __("responses.tournaments.full")
    ]);
});

it("cant_register_player_to_a_team_tournament", function () {
    $tournament = Tournament::factory()->create([
        'type' => 'team',
        'status' => 'open',
        'places' => 4
    ]);

    $user = User::factory()->create();

    $this->actingAs($user)->post('/api/tournaments/'.$tournament->id.'/register/'.$user->id)
    ->assertForbidden()
    ->assertJson([
        'status'  => "error",
        'message' => __("responses.tournaments.not_solo")
    ]);
});

it("cant_register_twice_player_to_same_tournament", function () {
    $tournament = Tournament::factory()->create([
        'type' => 'solo',
        'status' => 'open',
        'places' => 4
    ]);

    $user = User::factory()->create();

    // First register action (valid)
    $tournament->players()->attach($user);

    // Second register
    $this->actingAs($user)->post('/api/tournaments/'.$tournament->id.'/register/'.$user->id)
    ->assertForbidden()
    ->assertJson([
        'status'  => "error",
        'message' => __("responses.tournaments.already_registered")
    ]);
});

it("cant_unregister_twice_player_to_same_tournament", function () {
    $tournament = Tournament::factory()->create([
        'type' => 'solo',
        'status' => 'open',
        'places' => 4
    ]);

    $user = User::factory()->create();

    // Register action (valid)
    $tournament->players()->attach($user);

    // First unregister (valid)
    $this->actingAs($user)->post('/api/tournaments/'.$tournament->id.'/unregister/'.$user->id);

    // Second register (invalid)
    $this->actingAs($user)->post('/api/tournaments/'.$tournament->id.'/unregister/'.$user->id)
    ->assertForbidden()
    ->assertJson([
        'status'  => "error",
        'message' => __("responses.tournaments.not_registered")
    ]);
});
