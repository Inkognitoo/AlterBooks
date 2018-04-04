<?php

use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\Models\Book::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'description' => $faker->text(),
    ];
});
