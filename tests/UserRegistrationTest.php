<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserRegistrationTest extends TestCase
{
    //простое создание пользователя
    public function testNewUser()
    {
        //удачная регистрация
        $user_date = [
            'nickname' => 'Inkognitoo',
            'email' => 'inkognitoo1992@yandex.ru',
            'password' => '123456',
            'password_confirmation' => '123456',

            'name' => 'Павел',
            'surname' => 'Сотников',
            'patronymic' => 'Андреевич',
            'gender' => 'man'
        ];

        $response = $this->action('POST', 'UserController@registration', $user_date);
        $this->assertResponseOk();
        $json = json_decode($response->original, true);
        $this->assertEquals('success', $json['status']);
    }

    //тесты пользователя с повторяющимися данными
    public function testUserRepeat()
    {
        //регистрация пользователя с уже существующим email и ником
        $user_date = [
            'nickname' => 'Inkognitoo',
            'email' => 'inkognitoo1992@yandex.ru',
            'password' => '123456',
            'password_confirmation' => '123456',

            'name' => 'Павел',
            'surname' => 'Сотников',
            'patronymic' => 'Андреевич',
            'gender' => 'man'
        ];

        $response = $this->action('POST', 'UserController@registration', $user_date);
        $this->assertResponseStatus(402);
        $json = json_decode($response->original, true);
        $this->assertEquals('error', $json['status']);

        //регистрация пользователя с существующим email
        $user_date = [
            'nickname' => 'Inkognitoo2',
            'email' => 'inkognitoo1992@yandex.ru',
            'password' => '123456',
            'password_confirmation' => '123456',

            'name' => 'Павел',
            'surname' => 'Сотников',
            'patronymic' => 'Андреевич',
            'gender' => 'man'
        ];

        $response = $this->action('POST', 'UserController@registration', $user_date);
        $this->assertResponseStatus(402);
        $json = json_decode($response->original, true);
        $this->assertEquals('error', $json['status']);

        //регистрация пользователя с существующим nickname
        $user_date = [
            'nickname' => 'Inkognitoo',
            'email' => 'inkognitoo@yandex.ru',
            'password' => '123456',
            'password_confirmation' => '123456',

            'name' => 'Павел',
            'surname' => 'Сотников',
            'patronymic' => 'Андреевич',
            'gender' => 'man'
        ];

        $response = $this->action('POST', 'UserController@registration', $user_date);
        $this->assertResponseStatus(402);
        $json = json_decode($response->original, true);
        $this->assertEquals('error', $json['status']);
    }

    public function testUserWithoutParameters()
    {
        //Без ника и почты
        $user_date = [
            'password' => '123456',
            'password_confirmation' => '123456',

            'name' => 'Павел',
            'surname' => 'Сотников',
            'patronymic' => 'Андреевич',
            'gender' => 'man'
        ];

        $response = $this->action('POST', 'UserController@registration', $user_date);
        $this->assertResponseStatus(402);
        $json = json_decode($response->original, true);
        $this->assertEquals('error', $json['status']);

        //без ника
        $user_date = [
            'email' => 'inkognitoo@yandex.ru',
            'password' => '123456',
            'password_confirmation' => '123456',

            'name' => 'Павел',
            'surname' => 'Сотников',
            'patronymic' => 'Андреевич',
            'gender' => 'man'
        ];

        $response = $this->action('POST', 'UserController@registration', $user_date);
        $this->assertResponseStatus(402);
        $json = json_decode($response->original, true);
        $this->assertEquals('error', $json['status']);

        //регистрация пользователя без повтора пароля
        $user_date = [
            'nickname' => 'Inkognitoo92',
            'email' => 'inkognitoo@yandex.ru',
            'password' => '123456',

            'name' => 'Павел',
            'surname' => 'Сотников',
            'patronymic' => 'Андреевич',
            'gender' => 'man'
        ];

        $response = $this->action('POST', 'UserController@registration', $user_date);
        $this->assertResponseStatus(402);
        $json = json_decode($response->original, true);
        $this->assertEquals('error', $json['status']);

        //регистрация без пароля
        $user_date = [
            'nickname' => 'Inkognitoo92',
            'email' => 'inkognitoo@yandex.ru',
            'password_confirmation' => '123456',

            'name' => 'Павел',
            'surname' => 'Сотников',
            'patronymic' => 'Андреевич',
            'gender' => 'man'
        ];

        $response = $this->action('POST', 'UserController@registration', $user_date);
        $this->assertResponseStatus(402);
        $json = json_decode($response->original, true);
        $this->assertEquals('error', $json['status']);
    }

    public function testUserWithWrongParameters()
    {
        //регистрация пользователя с несовпадающими паролями
        $user_date = [
            'nickname' => 'Inkognitoo92',
            'email' => 'inkognitoo@yandex.ru',
            'password' => '123456',
            'password_confirmation' => '1234567',

            'name' => 'Павел',
            'surname' => 'Сотников',
            'patronymic' => 'Андреевич',
            'gender' => 'man'
        ];

        $response = $this->action('POST', 'UserController@registration', $user_date);
        $this->assertResponseStatus(402);
        $json = json_decode($response->original, true);
        $this->assertEquals('error', $json['status']);

        //регистрация пользователя с невалидным email
        $user_date = [
            'nickname' => 'Inkognitoo92',
            'email' => 'inkognitoo',
            'password' => '123456',
            'password_confirmation' => '123456',

            'name' => 'Павел',
            'surname' => 'Сотников',
            'patronymic' => 'Андреевич',
            'gender' => 'man'
        ];

        $response = $this->action('POST', 'UserController@registration', $user_date);
        $this->assertResponseStatus(402);
        $json = json_decode($response->original, true);
        $this->assertEquals('error', $json['status']);

        //регистрация пользователя с невалидным gender
        $user_date = [
            'nickname' => 'Inkognitoo92',
            'email' => 'inkognitoo@yandex.ru',
            'password' => '123456',
            'password_confirmation' => '123456',

            'name' => 'Павел',
            'surname' => 'Сотников',
            'patronymic' => 'Андреевич',
            'gender' => 'мужик'
        ];

        $response = $this->action('POST', 'UserController@registration', $user_date);
        $this->assertResponseStatus(402);
        $json = json_decode($response->original, true);
        $this->assertEquals('error', $json['status']);
    }
}
