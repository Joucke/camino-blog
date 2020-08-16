<?php

use App\Article;
use App\Tag;
use App\User;

test('guests cannot update tags', function () {
    $this->patchJson('/tags/1', ['title' => 'foobar'])
        ->assertUnauthorized();
});

test('users can update tags', function () {
    $tag = factory(Tag::class)->create();

    $this->actingAs(factory(User::class)->create())
        ->patchJson($tag->url, ['title' => 'foobar'])
        ->assertOk();

    $this->assertSame('foobar', $tag->fresh()->title);
});

test('when updating a tag with pivot, it returns the tag with pivot', function () {
    $tag = factory(Tag::class)->create();
    $this->actingAs(factory(User::class)->create())
        ->patchJson($tag->url, [
            'title' => 'new title',
            'pivot' => ['fake data'],
        ])->assertOk()
        ->assertJson([
            'pivot' => ['fake data'],
        ]);
});

test('users can update a tag from the article page', function () {
    $article = factory(Article::class)->states('published')->create();
    $tag = factory(Tag::class)->create();
    $article->tags()->attach($tag);

    $this->get('/articles/'.$article->slug)
        ->assertOk()
        ->assertDontSee('taggable-manager');

    $this->actingAs($article->author)
        ->get('/articles/'.$article->slug)
        ->assertOk()
        ->assertSee('taggable-manager');
});

it('validates these fields when updating a tag', function () {
    $tag = factory(Tag::class)->create();
    $this->actingAs(factory(User::class)->create())
        ->patch($tag->url, [
            'title' => '',
        ])
        ->assertSessionHasErrors([
            'title' => 'Titel is verplicht.',
        ]);

    factory(Tag::class)->create([
        'title' => 'foobar',
    ]);

    $this->patch($tag->url, ['title' => 'foobar'])
        ->assertSessionHasErrors([
            'title' => 'Titel moet uniek zijn.',
        ]);
});
