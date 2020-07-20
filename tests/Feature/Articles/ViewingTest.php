<?php

use App\Article;
use App\User;
use GrahamCampbell\Markdown\Facades\Markdown;

test('the index displays paginated articles', function () {
    factory(Article::class, 31)->states('published')->create();

    $this->get('/')
        ->assertSee('?page=2')
        ->assertSee('?page=3');
});

test('the index has a link to an article', function () {
    factory(Article::class)->create([
        'title' => 'my article',
        'body' => 'the article',
        'published_at' => now(),
    ]);

    $this->get('/')
        ->assertSeeInOrder([
            '<h3>',
            '<a href="/articles/my-article">my article</a>',
            '</h3>'
        ], false);
});

test('the index has a link to the article author', function () {
    $article = factory(Article::class)->create([
        'title' => 'my article',
        'body' => 'the article',
        'published_at' => now(),
    ]);

    $this->get('/')
        ->assertSee('<a href="/users/1">'.$article->author->name.'</a>', false);
});

test('the index shows the first paragraph of the article', function () {
    $article = factory(Article::class)->create([
        'title' => 'my article',
        'body' => "the article\r\nsecond paragraph",
        'published_at' => now(),
    ]);

    $this->get('/')
        ->assertSee('<p>the article</p>', false)
        ->assertDontSee('<p>second paragraph</p>', false)
        ;
});

test('the index shows the article tags')
    ->markTestIncomplete();

test('the index shows the article location')
    ->markTestIncomplete();

test('guests can view a published article', function () {
    $article = factory(Article::class)->states('published')->create();

    $parsedBody = Markdown::convertToHtml($article->body);
    $this->get('/articles/'.$article->slug)
        ->assertOk()
        ->assertSee($article->title)
        ->assertSee($article->author->name)
        ->assertSee($parsedBody, false)
        ;
});

test('guests cannot view an unpublished article', function () {
    $article = factory(Article::class)->create();

    $this->get('/articles/'.$article->slug)
        ->assertNotFound();
});

test('users can view a published article', function () {
    $article = factory(Article::class)->states('published')->create();

    $parsedBody = Markdown::convertToHtml($article->body);
    $this->actingAs(factory(User::class)->create())
        ->get('/articles/'.$article->slug)
        ->assertOk()
        ->assertSee($article->title)
        ->assertSee($article->author->name)
        ->assertSee($parsedBody, false)
        ;
});

test('users can view an unpublished article', function () {
    $article = factory(Article::class)->create();

    $parsedBody = Markdown::convertToHtml($article->body);
    $this->actingAs(factory(User::class)->create())
        ->get('/articles/'.$article->slug)
        ->assertOk()
        ->assertSee($article->title)
        ->assertSee($article->author->name)
        ->assertSee($parsedBody, false)
        ;
});
