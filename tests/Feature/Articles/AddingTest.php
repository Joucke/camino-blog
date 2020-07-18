<?php

use App\Article;

test('guests cannot add an article', function () {
    $this->get('/articles/create')
        ->assertRedirect('/login');

    $this->post('/articles', factory(Article::class)->raw())
        ->assertRedirect('/login');
});

test('users can add an article')
    ->markTestIncomplete();

test('users can add tags when creating an article')
    ->markTestIncomplete();

test('users can add a location when creating an article')
    ->markTestIncomplete();

test('users can add images when creating an article')
    ->markTestIncomplete();
