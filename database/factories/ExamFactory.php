<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Exam;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

$factory->define(Exam::class, function (Faker $faker) {
    return [
        'class_id' => $faker->numberBetween(1,5),
        'subject_id' => $faker->numberBetween(1,5),
        'base_score' => Arr::random([45,50,55,60]),
        'date' => Carbon::now(),
        'hours' => Arr::random([1,2,3,4]),
        'minutes' => 30
    ];
});
