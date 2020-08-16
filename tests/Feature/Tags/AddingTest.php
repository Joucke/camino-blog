<?php

use App\Tag;
use App\User;

test('guests cannot add tags', function () {
    $this->postJson('/tags', [])
        ->assertUnauthorized();
});

test('users can add tags', function () {
    $this->assertCount(0, Tag::all());

    $this->actingAs(factory(User::class)->create())
        ->withoutExceptionHandling()
        ->postJson('/tags', [
            'title' => 'foobar',
        ])
        ->assertOk()
        ->assertJson(Tag::orderBy('title')->get()->toArray());

    $this->assertCount(1, Tag::all());
});

it('validates these fields when creating a tag', function () {
    $this->actingAs(factory(User::class)->create())
        ->post('/tags', [
            'title' => '',
        ])
        ->assertSessionHasErrors([
            'title' => 'Titel is verplicht.',
        ]);

    factory(Tag::class)->create([
        'title' => 'foobar',
    ]);

    $this->post('/tags', ['title' => 'foobar'])
        ->assertSessionHasErrors([
            'title' => 'Titel moet uniek zijn.',
        ]);
});
