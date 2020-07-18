<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Article;
use App\User;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
    return [
        'author_id' => factory(User::class),
        'title' => $faker->sentence,
        'body' => implode("\n", $faker->paragraphs(3)),
    ];
});

$factory->state(Article::class, 'published', function (Faker $faker) {
    return factory(Article::class)->raw([
        'published_at' => now()->subDay(),
    ]);
});
