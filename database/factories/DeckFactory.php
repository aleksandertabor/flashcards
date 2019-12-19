<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Deck;
use Faker\Generator as Faker;

$languages = [
    'pl',
    'en',
];

$factory->define(Deck::class, function (Faker $faker) use ($languages) {
    return [
        'title' => $faker->catchPhrase,
        'description' => $faker->text(300),
        'lang_source' => $languages[0],
        'lang_target' => $languages[1],
        'visibility' => 'public',
        // 'slug' => $faker->slug(3),
    ];
});
