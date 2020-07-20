<?php

use App\Article;
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

test('guests see a list of tags on the home page')
    ->markTestIncomplete();

test('guests see a calendar view on the home page')
    ->markTestIncomplete();

test('guests see a link to "map view" on the home page')
    ->markTestIncomplete();
