<?php

use App\Location;
use App\User;

beforeEach(function () {
    $this->location = factory(Location::class)->create();
});

test('guests cannot edit a location', function () {
    $this->patch($this->location->url, [
        'title' => 'new title',
    ])->assertRedirect('/login');

    $this->patchJson($this->location->url, [
        'title' => 'new title',
    ])->assertUnauthorized();
});

test('users can update a location', function () {
    $this->actingAs(factory(User::class)->create())
        ->patch($this->location->url, [
            'title' => 'new title',
            'latitude' => 14,
            'longitude' => 27,
        ])->assertRedirect($this->location->fresh()->url);

    tap($this->location->fresh(), function ($location) {
        $this->assertSame('new title', $location->title);
        $this->assertSame('14.00000000', $location->latitude);
        $this->assertSame('27.00000000', $location->longitude);

        $this->patchJson($location->url, [
                'title' => 'newer title',
                'latitude' => 28,
                'longitude' => 54,
            ])
            ->assertOk()
            ->assertJson([
                'title' => 'newer title',
                'latitude' => 28,
                'longitude' => 54,
            ]);
    });
});

test('users can udpate a location from the article page', function () {
    // TODO: dusk test?
})->markTestIncomplete();

test('validation', function () {
    //
})->markTestIncomplete();
