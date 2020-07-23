<?php

use App\Article;
use App\Location;
use App\Tag;
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
            '<h3',
            'href="/articles/my-article">my article</a>',
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
        ->assertSee('href="/users/1">'.$article->author->name.'</a>', false);
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

test('the index shows the article location', function () {
    $article = factory(Article::class)->create([
        'title' => 'my article',
        'body' => "the article\r\nsecond paragraph",
        'published_at' => now(),
    ]);
    $home = factory(Location::class)->create(['title' => 'own house']);
    $article->locations()->attach($home);

    $this->get('/')
        ->assertSee('own house')
        ;
});

test('the index filters on location', function () {
    //
})->markTestIncomplete();

test('the index filters on tag', function () {
    //
})->markTestIncomplete();

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

test('guests can see a map with the article locations', function () {
    $article = factory(Article::class)->states('published')->create();
    $locations = factory(Location::class, 3)->create()->sortBy('title');
    $article->locations()->attach($locations);

    $locationTitles = $locations->pluck('title');

    $this->get('/articles/'.$article->slug)
        ->assertOk()
        ->assertSeeInOrder([
            'article-map',
            $locationTitles[0],
            $locationTitles[1],
            $locationTitles[2],
        ])
        ;
});

test('guests can see a list with article tags', function () {
    $article = factory(Article::class)->states('published')->create();
    $tags = factory(Tag::class, 3)->create();
    $article->tags()->attach($tags);

    $tagTitles = $tags->sortBy('title')->pluck('title');

    $this->withoutExceptionHandling()
        ->get('/articles/'.$article->slug)
        ->assertOk()
        ->assertSeeInOrder([
            $tagTitles[0],
            $tagTitles[1],
            $tagTitles[2],
        ])
        ;
});
