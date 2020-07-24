<?php

use App\Article;
use App\Location;
use App\Tag;

it('can be published', function () {
    $article = factory(Article::class)->create();

    $this->assertNull($article->published_at);

    $article->publish();

    $this->assertSame(
        now()->format('Y-m-d H:i:s'),
        $article->published_at->format('Y-m-d H:i:s'),
    );
});

it('has taggables', function () {
    $article = factory(Article::class)->states('published')->create();
    $location = factory(Location::class)->create();
    $tag = factory(Tag::class)->create();

    $article->locations()->attach($location);
    $article->tags()->attach($tag);

    $this->assertTrue($article->taggables->contains('url', $tag->url));
    $this->assertTrue($article->taggables->contains('url', $location->url));
});
