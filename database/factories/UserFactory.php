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

$factory->define(App\Models\User::class, function (Faker $faker) {
    $genders = [\App\Models\User::GENDER_MALE, \App\Models\User::GENDER_FEMALE, \App\Models\User::GENDER_NOT_INDICATED];
    $gender = $genders[rand(0, count($genders) - 1)];

    switch ($gender) {
        case \App\Models\User::GENDER_FEMALE:
            $gender_name = 'female';
            break;
        case \App\Models\User::GENDER_MALE:
            $gender_name = 'male';
            break;
        default:
            $gender_name = $faker->randomElement(['female', 'male']);
    }

    $full_name = $faker->name($gender_name);

    list($surname, $name, $patronymic) = explode(' ', $full_name);

    return [
        'nickname' => $faker->uuid,
        'name' => $name,
        'surname' => $surname,
        'patronymic' => $patronymic,
        'birthday_date' => $faker->date(),
        'gender' => $gender,
        'about' => $faker->realText(rand(100, 300)),
        'email' => $faker->unique()->safeEmail,
        'password' => 'secret',
        'remember_token' => str_random(10),
        'api_token' => str_random(60),
    ];
});
