<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tag;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Tag::class, function (Faker $faker) {
    return [
        'title' => $title = $faker->unique()->word,
        'slug' => Str::slug($title),
    ];
});
