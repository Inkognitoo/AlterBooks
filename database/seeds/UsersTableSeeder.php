<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $profile = New App\Profile();
        $profile->name = "Павел";
        $profile->surname = "Сотников";
        $profile->patronymic = "Андреевич";
        $profile->photo = '/nannaan.jpg';
        $profile->birthday = "2016-01-17";

        $user = new App\User();
        $user->nickname = "Inkognitoo";
        $user->email = "pavel@alterbooks.ru";
        $user->password = 'pass';
        $user->save();
        $user->profile()->save($profile);
    }
}
