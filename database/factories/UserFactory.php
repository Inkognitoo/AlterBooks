<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    return [
        'nickname' => $faker->uuid,
        'name' => $faker->firstName,
        'surname' => $faker->lastName,
        'patronymic' => $faker->lastName,
        'birthday_date' => $faker->date(),
        'gender' => function() {
            $genders = [\App\User::GENDER_MALE, \App\User::GENDER_FEMALE, \App\User::GENDER_NOT_INDICATED];

            return $genders[rand(0, 2)];
        },
        'about' => $faker->text,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'api_token' => str_random(60),
    ];
});
