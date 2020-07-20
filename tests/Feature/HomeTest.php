<?php

use App\Article;

it('loads the home page')
    ->get('/')
    ->assertOk();

test('guests see a login link on the home page')
    ->get('/')
    ->assertSee('/login');

test('guests do not see a register link on the home page')
    ->get('/')
    ->assertDontSee('/register');

test('users can logout')
    ->markTestIncomplete();

it('shows 15 paginated articles on the home page', function () {
    factory(Article::class)->states('published')->create(['title' => 'First article']);
    factory(Article::class, 20)->states('published')->create();

    $response = $this->get('/')
        ->assertSee('First article')
        ->assertSee('?page=2');

    $this->assertCount(15, $response['articles']);
});

test('guests see a calendar view on the home page')
    ->markTestIncomplete();

test('guests see a list of tags on the home page')
    ->markTestIncomplete();

test('guests see a link to "map view" on the home page')
    ->markTestIncomplete();

test('users see a link to add an article')
    ->markTestIncomplete();
