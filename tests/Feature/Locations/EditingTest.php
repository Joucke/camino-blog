<?php

use App\Location;
use App\User;

beforeEach(function () {
    $this->location = factory(Location::class)->create();
    $this->locationUrl = '/locations/' . $this->location->id;
});

test('guests cannot edit a location', function () {
    $this->patch($this->locationUrl, [
        'title' => 'new title',
    ])->assertRedirect('/login');

    $this->patchJson($this->locationUrl, [
        'title' => 'new title',
    ])->assertUnauthorized();
});

test('users can update a location', function () {
    $this->actingAs(factory(User::class)->create())
        ->patch($this->locationUrl, [
            'title' => 'new title',
            'latitude' => 14,
            'longitude' => 27,
        ])->assertRedirect($this->locationUrl);

    tap($this->location->fresh(), function ($location) {
        $this->assertSame('new title', $location->title);
        $this->assertSame('14', $location->latitude);
        $this->assertSame('27', $location->longitude);
    });

    $this->patchJson($this->locationUrl, [
            'title' => 'newer title',
            'latitude' => 28,
            'longitude' => 54,
        ])->assertOk()
        ->assertJson([
            'title' => 'newer title',
            'latitude' => 28,
            'longitude' => 54,
        ]);
});

test('users can udpate a location from the article page', function () {
    // TODO: dusk test?
})->markTestIncomplete();

test('validation', function () {
    //
})->markTestIncomplete();
