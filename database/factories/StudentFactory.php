<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Student;
use Faker\Generator as Faker;

$factory->define(Student::class, function (Faker $faker) {
    return [
        'firstname' => $faker->name,
        'lastname' => $faker->name,
        'examination_number' => $faker->numberBetween(5000,9000),
        'class_id' => $faker->numberBetween(1,3)
    ];
});
