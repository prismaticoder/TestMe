<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Question;
use Faker\Generator as Faker;

$factory->define(Question::class, function (Faker $faker) {
    return [
        'class_id' => $faker->numberBetween(1,3),
        'subject_id' => $faker->numberBetween(1,14),
        'question' => $faker->text()
    ];
});
