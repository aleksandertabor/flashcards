<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\FlashcardDeck;
use Faker\Generator as Faker;

$factory->define(FlashcardDeck::class, function (Faker $faker) {
    return [
        'title' => $faker->catchPhrase,
        'description' => $faker->text(),
    ];
});
