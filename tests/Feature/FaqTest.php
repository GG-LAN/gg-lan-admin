<?php

use App\Models\Faq;


it('can list faq', function () {

    Faq::factory(10)->create();

    $faqs = Faq::all();

    $this->get('/api/faq')
    ->assertOk()
    ->assertJson([
        "data" => $faqs->toArray()
    ]);
});
