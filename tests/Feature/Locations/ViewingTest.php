<?php

use App\Location;
use App\User;
use Illuminate\Support\Carbon;

test('guests cannot get raw location data', function () {
    $this->get('/locations')
        ->assertRedirect('/login');

    $this->getJson('/locations')
        ->assertUnauthorized();
});

test('users can get raw location data', function () {
    $locations = factory(Location::class, 2)->create();

    $this->actingAs(factory(User::class)->create())
        ->get('/locations')
        ->assertOk()
        ->assertSee($locations[0]->title)
        ->assertSee($locations[1]->title)
    ;

    $this->getJson('/locations')
        ->assertOk()
        ->assertJson($locations->toArray());
});

test('guests cannot get raw single location data', function () {
    $location = factory(Location::class)->create();

    $this->get($location->url)
        ->assertRedirect('/login');

    $this->getJson($location->url)
        ->assertUnauthorized();
});

test('users can get raw single location data', function () {
    $location = factory(Location::class)->create();

    $this->actingAs(factory(User::class)->create())
        ->withoutExceptionHandling()
        ->get($location->url)
        ->assertOk()
        ->assertSee($location->title);

    $this->getJson($location->url)
        ->assertOk()
        ->assertJson($location->toArray());
});

test('locations are sorted latest-first', function () {
    Carbon::setTestNow(now()->subMonth());
    $old = factory(Location::class)->create();
    Carbon::setTestNow(now()->addMonth());
    $new = factory(Location::class)->create();

    $this->actingAs(factory(User::class)->create())
        ->getJson('/locations')
        ->assertJson([
            $new->toArray(),
            $old->toArray(),
        ]);
});
