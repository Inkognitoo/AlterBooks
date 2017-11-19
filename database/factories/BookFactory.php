<?php

use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\Book::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'description' => $faker->text(),
    ];
});
