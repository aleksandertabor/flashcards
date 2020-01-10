<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Deck;
use App\Language;
use Faker\Generator as Faker;

$languages = Language::pluck('id')->all();

$factory->define(Deck::class, function (Faker $faker) use ($languages) {
    $randomLanguages = array_rand(array_flip($languages), 2);
    return [
        'title' => $faker->catchPhrase,
        'description' => $faker->text(300),
        'lang_source_id' => $randomLanguages[0],
        'lang_target_id' => $randomLanguages[1],
        'visibility' => array_key_first(Deck::visibilities()[mt_rand(0, count(Deck::visibilities()) - 1)]),
        'created_at' => $faker->dateTime('now', null),
        // 'slug' => $faker->slug(3),
    ];
});
