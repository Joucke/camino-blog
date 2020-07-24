<?php

use App\Tag;

it('gets an url attribute', function () {
    $tag = factory(Tag::class)->create(['title' => 'Somewhere']);

    $this->assertSame('/tags/somewhere', $tag->url);
});
