<?php

use App\Article;
use App\User;

beforeEach(function () {
    $this->article = factory(Article::class)->create();
    $this->articleUrl = '/articles/' . $this->article->slug;
});

test('guests cannot edit an article', function () {
    $this->get($this->articleUrl . '/edit')
        ->assertRedirect('/login');

    $this->patch($this->articleUrl, [])
        ->assertRedirect('/login');
});

test('users can edit an article', function () {
    $this->actingAs($this->article->author)
        ->get($this->articleUrl . '/edit')
        ->assertOk();

    $this->patch($this->articleUrl, [
            'title' => 'updated title',
            'body' => 'updated body',
        ])
        ->assertRedirect('/articles/updated-title');

    tap($this->article->fresh(), function ($article) {
        $this->assertSame('updated title', $article->title);
        $this->assertSame('updated body', $article->body);
        $this->assertNull($article->published_at);
    });
});

test('users can publish when editing an article', function () {
    $this->assertGuest();

    $this->get('/')
        ->assertDontSee($this->article->title);

    $this->actingAs($this->article->author)
        ->patch($this->articleUrl, [
            'title' => 'updated title',
            'body' => 'updated body',
            'published_at' => now()->subHour()
        ])
        ->assertRedirect('/articles/updated-title');

    $this->post('/logout');
    $this->assertGuest();

    tap($this->article->fresh(), function ($article) {
        $this->assertSame('updated title', $article->title);
        $this->assertSame('updated body', $article->body);
        $this->assertNotNull($article->published_at);

        $this->get('/')
            ->assertSee($article->title);
    });

});

test('users can add images when editing an article', function () {
    //
})->markTestIncomplete();

test('users can change tags when editing an article', function () {
    //
})->markTestIncomplete();

test('users can change a location when editing an article', function () {
    //
})->markTestIncomplete();