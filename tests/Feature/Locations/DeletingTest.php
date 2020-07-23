<?php

use App\Article;
use App\Location;
use App\User;

test('guests cannot delete a location', function () {
    $this->get('/locations')
        ->assertDontSee('Verwijderen');

    $this->delete('/locations/1')
        ->assertRedirect('/login');
});

test('users can delete a location', function () {
    $l = factory(Location::class)->create();
    $this->assertCount(1, Location::all());

    $this->actingAs(factory(User::class)->create())
        ->get('/locations')
        ->assertOk()
        ->assertSee('Verwijderen');

    $this->delete('/locations/1')
        ->assertRedirect('/locations');

    $this->assertCount(0, Location::all());
});

test('users can only delete a location that is no longer used', function () {
    $location = factory(Location::class)->create();
    $article = factory(Article::class)->states('published')->create();
    $article->locations()->attach($location);
    $this->assertCount(1, Location::all());

    $this->actingAs($article->author)
        ->get('/locations')
        ->assertOk()
        ->assertSee('Verwijderen');

    $this->delete('/locations/1')
        ->assertRedirect('/locations')
        ->assertSessionHas('error')
    ;

    $this->assertCount(1, Location::all());
});
