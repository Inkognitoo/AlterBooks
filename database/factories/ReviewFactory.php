<?php

use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\Models\Review::class, function (Faker $faker) {
    return [
        'rating' => rand(1, 10),
        'text' => $faker->realText(rand(100, 500)),
    ];
});
