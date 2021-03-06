<?php

use App\Article;
use App\Location;
use App\Tag;
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
    $this->actingAs(factory(User::class)->create())
        ->get($this->articleUrl . '/edit')
        ->assertSee('<upload-form />', false);
});

test('users can change a location when editing an article', function () {
    $home = factory(Location::class)->create();
    $work = factory(Location::class)->create();
    $this->article->locations()->attach($home);

    $this->assertTrue($this->article->fresh()->locations->first()->is($home));

    $this->actingAs(factory(User::class)->create())
        ->patch($this->articleUrl, [
            'title' => 'my article',
            'body' => 'this is my article',
            'locations' => [
                $work->id,
            ],
        ])
        ->assertRedirect('/articles/my-article');

    tap($this->article->fresh(), function ($article) use ($home, $work) {
        $this->assertCount(1, $article->locations);
        $this->assertFalse($article->locations->first()->is($home));
        $this->assertTrue($article->locations->first()->is($work));
    });
});

test('users can change tags when editing an article', function () {
    $home = factory(Tag::class)->create();
    $work = factory(Tag::class)->create();
    $this->article->tags()->attach($home);

    $this->assertTrue($this->article->fresh()->tags->first()->is($home));

    $this->actingAs(factory(User::class)->create())
        ->patch($this->articleUrl, [
            'title' => 'my article',
            'body' => 'this is my article',
            'tags' => [
                $work->id,
            ],
        ])
        ->assertRedirect('/articles/my-article');

    tap($this->article->fresh(), function ($article) use ($home, $work) {
        $this->assertCount(1, $article->tags);
        $this->assertFalse($article->tags->first()->is($home));
        $this->assertTrue($article->tags->first()->is($work));
    });
});

it('validates these fields when updating an article', function () {
    $this->actingAs($this->article->author)
        ->patch($this->articleUrl, [
            'title' => '',
            'body' => '',
        ])
        ->assertSessionHasErrors([
            'title' => 'Titel is verplicht.',
            'body' => 'Inhoud is verplicht.',
        ]);

    factory(Article::class)->create(['title' => 'the title']);

    $this->actingAs($this->article->author)
        ->patch($this->articleUrl, [
            'title' => 'the title',
        ])
        ->assertSessionHasErrors([
            'title' => 'Titel moet uniek zijn.',
        ]);
});
