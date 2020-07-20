<?php

use App\Article;
use App\User;

test('guests cannot add an article', function () {
    $this->get('/articles/create')
        ->assertRedirect('/login');

    $this->post('/articles', factory(Article::class)->raw())
        ->assertRedirect('/login');
});

test('users can add an article', function () {
    $user = factory(User::class)->create();

    $this->assertCount(0, Article::all());

    $this->actingAs($user)
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
    $user = factory(User::class)->create();

    $this->actingAs($user)
        ->post('/articles', [
        'title' => 'my article',
        'body' => 'this is my article',
        'published_at' => now(),
    ])
        ->assertRedirect('/');

    $this->get('/')
        ->assertSeeInOrder(['h3', 'articles/my-article', 'my article']);
});

test('users can add tags when creating an article')
    ->markTestIncomplete();

test('users can add a location when creating an article')
    ->markTestIncomplete();

test('users can add images when creating an article')
    ->markTestIncomplete();
