<?php

use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\Models\Blog\Article::class, function (Faker $faker) {

    do {
        $title = 'Статья ' . $faker->realText(random_int(10, 20));
        $title = substr($title, 0, -1);
    } while (blank($title));

    return [
        'title' => mb_convert_encoding($title, 'UTF-8'),
        'text' => mb_convert_encoding($faker->realText(random_int(3000, 8000)), 'UTF-8')
    ];
});
