<?php

use App\Article;
use App\Location;
use App\Tag;
use App\User;

it('loads the home page')
    ->get('/')
    ->assertOk();

test('guests see a login link on the home page')
    ->get('/')
    ->assertSee('/login');

test('guests do not see a register link on the home page')
    ->get('/')
    ->assertDontSee('/register');

test('users can logout', function () {
    $user = factory(User::class)->create();
    $this->actingAs($user)
        ->get('/')
        ->assertSee('/logout');

    $this->post('/logout')
        ->assertLocation('/');

    $this->get('/')
        ->assertSee('/login');
});

it('shows 15 paginated articles on the home page', function () {
    factory(Article::class)->create([
        'title' => 'My article',
        'published_at' => now()->subHour(),
    ]);
    factory(Article::class, 20)->create([
        'published_at' => now()->subDay(),
    ]);

    $response = $this->get('/')
        ->assertSee('My article')
        ->assertSee('?page=2');

    $this->assertCount(15, $response['articles']);
});

test('users see a link to add an article', function () {
    $user = factory(User::class)->create();

    $this->actingAs($user)
        ->get('/')
        ->assertSee('/articles/create');
});

test('guests see a link to "map view" on the home page', function () {
    $location = factory(Location::class)->create();
    $article = factory(Article::class)->states('published')->create();
    $article->locations()->attach($location);

    $this->get('/')
        ->assertSee('article-map')
        ->assertSee('"title":"'.$location->title.'"');
});

test('guests see a list of tags on the home page', function () {
    $tag = factory(Tag::class)->create();

    $this->get('/')
        ->assertSee($tag->title);
});

test('guests see a calendar view on the home page', function () {
    $article = factory(Article::class)->create([
        'published_at' => '2020-07-01 10:00:00',
    ]);
    $article = factory(Article::class)->create([
        'published_at' => '2020-06-01 10:00:00',
    ]);
    $article = factory(Article::class)->create([
        'published_at' => '2020-05-01 10:00:00',
    ]);

    $this->get('/')
        ->assertSee('"/2020/07"', false)
        ->assertSee('"/2020/06"', false)
        ->assertSee('"/2020/05"', false)
    ;
});
