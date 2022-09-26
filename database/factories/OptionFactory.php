<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Option;
use Faker\Generator as Faker;

$factory->define(Option::class, function (Faker $faker) {
    return [
        'question_id' => $faker->numberBetween(1,700),
        'body' => $faker->text(),
        'is_correct' => $faker->boolean(25),
    ];
});
