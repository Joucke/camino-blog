<?php

use App\Article;
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

test('when updating a location with pivot, it returns the location with pivot', function () {
    $this->actingAs(factory(User::class)->create())
        ->patchJson($this->location->url, [
            'title' => 'new title',
            'latitude' => 14,
            'longitude' => 27,
            'pivot' => ['fake data'],
        ])->assertOk()
        ->assertJson([
            'pivot' => ['fake data'],
        ]);
});

test('users can update a location from the article page', function () {
    $article = factory(Article::class)->states('published')->create();
    $article->locations()->attach($this->location);

    $this->get('/articles/'.$article->slug)
        ->assertOk()
        ->assertDontSee('taggable-manager');

    $this->actingAs($article->author)
        ->get('/articles/'.$article->slug)
        ->assertOk()
        ->assertSee('taggable-manager');
});

it('validates these fields when updating a location', function () {
    $this->actingAs(factory(User::class)->create())
        ->patch($this->location->url, [
            'title' => '',
            'latitude' => '',
            'longitude' => '',
        ])
        ->assertSessionHasErrors([
            'title' => 'Titel is verplicht.',
            'latitude' => 'Latitude is verplicht.',
            'longitude' => 'Longitude is verplicht.',
        ]);

    factory(Location::class)->create([
        'title' => 'foobar',
    ]);

    $this->patch($this->location->url, ['title' => 'foobar'])
        ->assertSessionHasErrors([
            'title' => 'Titel moet uniek zijn.',
        ]);
});
