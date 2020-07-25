<?php

use App\Article;
use App\Location;
use App\User;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->article = factory(Article::class)->create();
    $this->articleUrl = '/articles/' . $this->article->slug;
});

test('guests cannot delete an article', function () {
    $this->get($this->articleUrl)
        ->assertDontSee('type="submit">Verwijderen</button>');

    $this->delete($this->articleUrl)
        ->assertRedirect('/login');
});

test('users can delete an article', function () {
    $this->assertCount(1, Article::all());

    $this->actingAs($this->article->author);
        // ->get($this->articleUrl)
        // ->assertSee('type="submit">Verwijderen</button>', false);

    $this->delete($this->articleUrl)
        ->assertRedirect('/');

    $this->assertCount(0, Article::all());
});

it('removes the link to locations when deleting an article, but not the location itself', function () {
    $l = factory(Location::class)->create();
    $this->article->locations()->attach($l);
    $this->article->publish();

    $this->assertCount(1, Location::all());
    $this->assertCount(1, $l->articles()->get());

    $this->actingAs($this->article->author)
        ->delete($this->articleUrl)
        ->assertRedirect('/');

    $this->assertCount(1, Location::all());
    $this->assertCount(0, $l->articles()->get());
});
