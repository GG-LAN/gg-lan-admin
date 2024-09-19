<?php

use App\Models\Setting;

it('can get location', function () {
    $location = [
        "address" => 'valid address',
        "longitude" => -4.391945,
        "latitude" => 48.402466,
    ];

    Setting::set("lan_location", json_encode($location));

    $this->get('/api/location')
        ->assertOk()
        ->assertJson(["data" => $location]);
});
