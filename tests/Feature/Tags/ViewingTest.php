<?php

use App\Article;
use App\Tag;
use App\User;

test('guests cannot see raw tag data', function () {
    $this->getJson('/tags')
        ->assertUnauthorized();
});

test('users can see raw tag data', function () {
    factory(Tag::class, 15)->create();

    $this->actingAs(factory(User::class)->create())
        ->getJson('/tags')
        ->assertOk()
        ->assertJson(Tag::orderBy('title')->get()->toArray());
});

it('shows articles by tag', function () {
    $tag = factory(Tag::class)->create();
    $articles = factory(Article::class, 3)
        ->states('published')
        ->create();
    $otherArticle = factory(Article::class)
        ->states('published')
        ->create();
    $tag->articles()->attach($articles);

    $this->get($tag->url)
        ->assertSee($articles[0]->title)
        ->assertSee($articles[1]->title)
        ->assertSee($articles[2]->title)
        ->assertDontSee($otherArticle->title)
        ;
});
