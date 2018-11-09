<?php

use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\Models\Review::class, function (Faker $faker) {
    return [
        'rating' => random_int(1, 10),
        'header' => mb_convert_encoding($faker->realText(random_int(20, 67)), 'UTF-8'),
        'text' => mb_convert_encoding($faker->realText(random_int(100, 500)), 'UTF-8'),
    ];
});
