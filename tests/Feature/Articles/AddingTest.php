<?php

use App\Article;
use App\Location;
use App\User;

test('guests cannot add an article', function () {
    $this->get('/articles/create')
        ->assertRedirect('/login');

    $this->post('/articles', factory(Article::class)->raw())
        ->assertRedirect('/login');
});

test('users can add an article', function () {
    $this->assertCount(0, Article::all());

    $this->actingAs(factory(User::class)->create())
        ->get('/articles/create')
        ->assertOk();

    $this->post('/articles', [
        'title' => 'my article',
        'body' => 'this is my article',
    ])
        ->assertRedirect('/');

    $this->get('/')
        ->assertSee('my article', 'logged in users should see their unpublished articles as well');

    $this->assertCount(1, Article::all());
});

test('users can publish when creating an article', function () {
    $this->actingAs(factory(User::class)->create())
            ->post('/articles', [
            'title' => 'my article',
            'body' => 'this is my article',
            'published_at' => now(),
        ])
        ->assertRedirect('/');

    $this->get('/')
        ->assertSeeInOrder(['h3', 'articles/my-article', 'my article']);
});

test('users can add images when creating an article', function () {
    $this->actingAs(factory(User::class)->create())
        ->get('/articles/create')
        ->assertSee('<upload-form />', false);
});

test('users can add a location when creating an article', function () {
    $location = factory(Location::class)->create();

    $this->actingAs(factory(User::class)->create())
        ->post('/articles', [
            'title' => 'my article',
            'body' => 'this is my article',
            'locations' => [
                $location->id,
            ],
        ])
        ->assertRedirect('/');

    $this->assertCount(1, Article::all());
    $article = Article::first();
    $this->assertCount(1, $article->locations);
    $this->assertTrue($article->locations->first()->is($location));
});

test('users can add tags when creating an article')
    ->markTestIncomplete();
