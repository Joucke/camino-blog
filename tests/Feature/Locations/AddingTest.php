<?php

use App\Location;
use App\User;

test('a guest cannot add a location', function () {
    $this->post('/locations')
        ->assertRedirect('/login');
});

test('a user can add a location', function () {
    $data = factory(Location::class)->raw();
    $this->actingAs(factory(User::class)->create())
        ->postJson('/locations', $data)
        ->assertOk();

    $this->assertCount(1, Location::all());
});

test('a user can supply these fields for a location', function () {
    $data = factory(Location::class)->raw([
        'title' => 'home',
        'latitude' => 51.643191,
        'longitude' => 5.291407,
    ]);
    $this->actingAs(factory(User::class)->create())
        ->postJson('/locations', $data)
        ->assertOk();

    $this->assertCount(1, Location::all());
});
