<?php

use App\Tag;
use App\User;

test('guests cannot update tags', function () {
    $this->patchJson('/tags/1', ['title' => 'foobar'])
        ->assertUnauthorized();
});

test('users can update tags', function () {
    $tag = factory(Tag::class)->create();

    $this->actingAs(factory(User::class)->create())
        ->patchJson('/tags/1', ['title' => 'foobar'])
        ->assertOk();

    $this->assertSame('foobar', $tag->fresh()->title);
});

test('users can edit a tag from the article page', function () {
    //
})->markTestIncomplete();

test('validation', function () {
    //
})->markTestIncomplete();
