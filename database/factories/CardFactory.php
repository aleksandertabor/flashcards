<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Card;
use Faker\Generator as Faker;

$factory->define(Card::class, function (Faker $faker) {
    return [
        'question' => $faker->word(),
        'answer' => $faker->word(),
        'example_question' => $faker->text(240),
        'example_answer' => $faker->text(240),
    ];
});
