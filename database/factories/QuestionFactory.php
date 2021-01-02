<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Question;
use Faker\Generator as Faker;

$factory->define(Question::class, function (Faker $faker) {
    return [
        'exam_id' => $faker->numberBetween(1,3),
        'question' => $faker->text()
    ];
});
