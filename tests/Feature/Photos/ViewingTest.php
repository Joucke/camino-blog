<?php

use App\Photo;
use App\User;
use Illuminate\Support\Carbon;

test('guests cannot load a photo index', function () {
    $this->get('/photos')
        ->assertRedirect('/login');
});

test('users can load a photo index', function () {
    $this->actingAs(factory(User::class)->create())
        ->get('/photos')
        ->assertOk();
});

test('photos are sorted latest-first', function () {
    Carbon::setTestNow(now()->subMonth());
    $old = factory(Photo::class)->create();
    Carbon::setTestNow(now()->addMonth());
    $new = factory(Photo::class)->create();

    $this->actingAs(factory(User::class)->create())
        ->getJson('/photos')
        ->assertJson([
            $new->toArray(),
            $old->toArray(),
        ]);
});
