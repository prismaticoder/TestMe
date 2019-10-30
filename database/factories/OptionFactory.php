<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Option;
use Faker\Generator as Faker;

$factory->define(Option::class, function (Faker $faker) {
    return [
        'question_id' => $faker->numberBetween(51,100),
        'body' => $faker->text(),
        'isCorrect' => $faker->boolean(25),
    ];
});
