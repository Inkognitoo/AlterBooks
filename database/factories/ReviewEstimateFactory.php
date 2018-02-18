<?php

use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\ReviewEstimate::class, function (Faker $faker) {
    return [
        'estimate' => rand(-1, 1),
    ];
});
