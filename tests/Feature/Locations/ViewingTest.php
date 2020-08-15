<?php

use App\Article;
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

it('shows articles by location', function () {
    $location = factory(Location::class)->create();
    $articles = factory(Article::class, 3)
        ->states('published')
        ->create();
    $otherArticle = factory(Article::class)
        ->states('published')
        ->create();
    $location->articles()->attach($articles);

    $this->get($location->url)
        ->assertSee($articles[0]->title)
        ->assertSee($articles[1]->title)
        ->assertSee($articles[2]->title)
        ->assertDontSee($otherArticle->title)
        ;
});
