<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Score;
use Faker\Generator as Faker;

$factory->define(Score::class, function (Faker $faker) {
    return [
        'subject_id' => $faker->numberBetween(1,15),
        'class_id' => $faker->numberBetween(1,3),
        'student_id' => $faker->numberBetween(19,34),
        'score' => $faker->numberBetween(30,50),
    ];
});
