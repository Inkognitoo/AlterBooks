<?php

use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\Models\Book::class, function (Faker $faker) {

    do {
        $title = $faker->realText(rand(10, 20));
    } while (blank($title));

    return [
        'title' => $title,
        'description' => $faker->realText(rand(100, 200)),
    ];
});
