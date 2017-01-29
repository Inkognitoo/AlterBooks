<?php
/**
 * Created by PhpStorm.
 * User: inkognitoo
 * Date: 14.05.16
 * Time: 18:07
 */

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserAuthTest extends TestCase
{
    //простое создание пользователя
    public function testNewUser()
    {
        //удачная регистрация
        $user_date = [
            'nickname' => 'TEST',
            'email' => 'test@yandex.ru',
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

    public function testAuth()
    {
        $auth_date = [
            'login' => 'test@yandex.ru',
            'password' => '123456'
        ];
        $response = $this->action('POST', 'UserController@auth', $auth_date);
        $this->assertResponseOk();
        $json = json_decode($response->original, true);
        $this->assertEquals('success', $json['status']);

        $auth_date = [
            'login' => 'TEST@YaNdEx.Ru',
            'password' => '123456'
        ];
        $response = $this->action('POST', 'UserController@auth', $auth_date);
        $this->assertResponseOk();
        $json = json_decode($response->original, true);
        $this->assertEquals('success', $json['status']);
    }

    public function testAuthWithoutParameters()
    {
        $auth_date = [
            'password' => '123456'
        ];
        $response = $this->action('POST', 'UserController@auth', $auth_date);
        $this->assertResponseStatus(400);
        $json = json_decode($response->original, true);
        $this->assertEquals('error', $json['status']);

        $auth_date = [
            'login' => 'test@yandex.ru',
        ];
        $response = $this->action('POST', 'UserController@auth', $auth_date);
        $this->assertResponseStatus(400);
        $json = json_decode($response->original, true);
        $this->assertEquals('error', $json['status']);

        $auth_date = [
            'login' => '',
            'password' => '123456'
        ];
        $response = $this->action('POST', 'UserController@auth', $auth_date);
        $this->assertResponseStatus(400);
        $json = json_decode($response->original, true);
        $this->assertEquals('error', $json['status']);

        $auth_date = [
            'login' => 'test@yandex.ru',
            'password' => ''
        ];
        $response = $this->action('POST', 'UserController@auth', $auth_date);
        $this->assertResponseStatus(400);
        $json = json_decode($response->original, true);
        $this->assertEquals('error', $json['status']);
    }

    public function testAuthWithWrongParameters()
    {
        $auth_date = [
            'login' => 'test@yandex.ru',
            'password' => '1234567'
        ];
        $response = $this->action('POST', 'UserController@auth', $auth_date);
        $this->assertResponseStatus(400);
        $json = json_decode($response->original, true);
        $this->assertEquals('error', $json['status']);

        $auth_date = [
            'login' => 'tests@yandex.ru',
            'password' => '123456'
        ];
        $response = $this->action('POST', 'UserController@auth', $auth_date);
        $this->assertResponseStatus(400);
        $json = json_decode($response->original, true);
        $this->assertEquals('error', $json['status']);
    }

    public function testUnauth()
    {
        $auth_date = [
            'login' => 'test@yandex.ru',
            'password' => '123456'
        ];
        $response = $this->action('POST', 'UserController@auth', $auth_date);
        $this->assertResponseOk();
        $json = json_decode($response->original, true);
        $this->assertEquals('success', $json['status']);

        $response = $this->action('DELETE', 'UserController@unauth');
        $this->assertResponseOk();
        $json = json_decode($response->original, true);
        $this->assertEquals('success', $json['status']);

        $response = $this->action('DELETE', 'UserController@unauth');
        $this->assertResponseStatus(401);
        $json = json_decode($response->original, true);
        $this->assertEquals('error', $json['status']);
    }
}