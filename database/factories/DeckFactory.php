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
        'lang_source' => array_rand(array_flip($languages)),
        'lang_target' => array_rand(array_flip($languages)),
        'visibility' => Deck::visibilities()[mt_rand(0, count(Deck::visibilities()) - 1)],
        'created_at' => $faker->dateTime('now', null),
        // 'slug' => $faker->slug(3),
    ];
});
