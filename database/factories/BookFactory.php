<?php

use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\Models\Book::class, function (Faker $faker) {

    do {
        $title = 'Книга ' . $faker->realText(rand(10, 20));
        $title = substr($title, 0, -1);
    } while (blank($title));

    return [
        'title' => mb_convert_encoding($title, 'UTF-8'),
        'description' => mb_convert_encoding($faker->realText(rand(100, 200)), 'UTF-8'),
    ];
});
