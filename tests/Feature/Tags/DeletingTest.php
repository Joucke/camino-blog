<?php

use App\Article;
use App\Tag;
use App\User;

test('guests cannot delete tags', function () {
    $this->deleteJson('/tags/1')
        ->assertUnauthorized();
});

test('users can delete tags', function () {
    $tag = factory(Tag::class)->create();

    $this->assertCount(1, Tag::all());

    $this->actingAs(factory(User::class)->create())
        ->deleteJson('/tags/1')
        ->assertOk()
        ->assertJson([]);

    $this->assertCount(0, Tag::all());
});

test('only tags without articles can be deleted', function () {
    $tag = factory(Tag::class)->create();
    $article = factory(Article::class)->states('published')->create();

    $article->tags()->attach($tag);

    $response = $this->actingAs($article->author)
        ->withoutExceptionHandling()
        ->deleteJson('/tags/1')
        ->assertStatus(422)
        ->json()
    ;
    $this->assertArrayHasKey('error', $response);
});
