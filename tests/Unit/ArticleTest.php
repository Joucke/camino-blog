<?php

use App\Article;

it('can be published', function () {
    $article = factory(Article::class)->create();

    $this->assertNull($article->published_at);

    $article->publish();

    $this->assertSame(
        now()->format('Y-m-d H:i:s'),
        $article->published_at->format('Y-m-d H:i:s'),
    );
});
