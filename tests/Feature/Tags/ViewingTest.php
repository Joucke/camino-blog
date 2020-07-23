<?php

use App\Tag;
use App\User;

test('guests cannot see raw tag data', function () {
    $this->getJson('/tags')
        ->assertUnauthorized();
});

test('users can see raw tag data', function () {
    factory(Tag::class, 15)->create();

    $this->actingAs(factory(User::class)->create())
        ->getJson('/tags')
        ->assertOk()
        ->assertJson(Tag::orderBy('title')->get()->toArray());
});
