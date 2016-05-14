<?php

/**
 * Created by PhpStorm.
 * User: inkognitoo
 * Date: 14.05.16
 * Time: 19:27
 */

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FillingUserProfileTest extends TestCase
{
    public function testNewUser()
    {
        //удачная регистрация
        $user_date = [
            'nickname' => 'TEST_THREE',
            'email' => 'test_three@yandex.ru',
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

    //TODO: чекать, действительно ли новые данные установились
    public function testFilling()
    {
        $auth_date = [
            'login' => 'test_three@yandex.ru',
            'password' => '123456',
        ];
        $response = $this->action('POST', 'UserController@auth', $auth_date);
        $this->assertResponseOk();
        $json = json_decode($response->original, true);
        $this->assertEquals('success', $json['status']);

        $profile_date = [
            'nickname' => 'TEST_THREE1',
            'name' => 'Павел',
            'surname' => 'Сотников',
            'patronymic' => 'Андреевич',
            'gender' => 'man',
            'birthday' => '1992-04-27'
        ];
        $response = $this->action('PUT', 'UserController@fillingProfile', $profile_date);
        $this->assertResponseOk();
        $json = json_decode($response->original, true);
        $this->assertEquals('success', $json['status']);

        $profile_date = [
            'nickname' => 'TEST_THREE',
        ];
        $response = $this->action('PUT', 'UserController@fillingProfile', $profile_date);
        $this->assertResponseOk();
        $json = json_decode($response->original, true);
        $this->assertEquals('success', $json['status']);

        $profile_date = [
            'name' => 'Павел',
        ];
        $response = $this->action('PUT', 'UserController@fillingProfile', $profile_date);
        $this->assertResponseOk();
        $json = json_decode($response->original, true);
        $this->assertEquals('success', $json['status']);

        $profile_date = [
            'surname' => 'Сотников',
        ];
        $response = $this->action('PUT', 'UserController@fillingProfile', $profile_date);
        $this->assertResponseOk();
        $json = json_decode($response->original, true);
        $this->assertEquals('success', $json['status']);

        $profile_date = [
            'patronymic' => 'Андреевич',
        ];
        $response = $this->action('PUT', 'UserController@fillingProfile', $profile_date);
        $this->assertResponseOk();
        $json = json_decode($response->original, true);
        $this->assertEquals('success', $json['status']);

        $profile_date = [
            'gender' => 'woman',
            'birthday' => '1992-04-27'
        ];
        $response = $this->action('PUT', 'UserController@fillingProfile', $profile_date);
        $this->assertResponseOk();
        $json = json_decode($response->original, true);
        $this->assertEquals('success', $json['status']);
    }

    public function testFillingWithBadParameters()
    {
        $auth_date = [
            'login' => 'test_three@yandex.ru',
            'password' => '123456',
        ];
        $response = $this->action('POST', 'UserController@auth', $auth_date);
        $this->assertResponseOk();
        $json = json_decode($response->original, true);
        $this->assertEquals('success', $json['status']);

        $profile_date = [
            'nickname' => 'TEST_THREE',
            'name' => 'Павел',
            'surname' => 'Сотников',
            'patronymic' => 'Андреевич',
            'gender' => 'man',
            'birthday' => '1992-04-27'
        ];
        $response = $this->action('PUT', 'UserController@fillingProfile', $profile_date);
        $this->assertResponseStatus(400);
        $json = json_decode($response->original, true);
        $this->assertEquals('error', $json['status']);

        $profile_date = [
            'nickname' => 'id22',
            'name' => 'Павел',
            'surname' => 'Сотников',
            'patronymic' => 'Андреевич',
            'gender' => 'man',
            'birthday' => '1992-04-27'
        ];
        $response = $this->action('PUT', 'UserController@fillingProfile', $profile_date);
        $this->assertResponseStatus(400);
        $json = json_decode($response->original, true);
        $this->assertEquals('error', $json['status']);

        $profile_date = [
            'nickname' => 'book',
            'name' => 'Павел',
            'surname' => 'Сотников',
            'patronymic' => 'Андреевич',
            'gender' => 'man',
            'birthday' => '1992-04-27'
        ];
        $response = $this->action('PUT', 'UserController@fillingProfile', $profile_date);
        $this->assertResponseStatus(400);
        $json = json_decode($response->original, true);
        $this->assertEquals('error', $json['status']);

        $profile_date = [
            'nickname' => 'TestNick777',
            'name' => 'Павел',
            'surname' => 'Сотников',
            'patronymic' => 'Андреевич',
            'gender' => 'мужик',
            'birthday' => '1992-04-27'
        ];
        $response = $this->action('PUT', 'UserController@fillingProfile', $profile_date);
        $this->assertResponseStatus(400);
        $json = json_decode($response->original, true);
        $this->assertEquals('error', $json['status']);

        $profile_date = [
            'nickname' => 'TestNick777',
            'name' => 'Павел',
            'surname' => 'Сотников',
            'patronymic' => 'Андреевич',
            'birthday' => '1992-04-271'
        ];
        $response = $this->action('PUT', 'UserController@fillingProfile', $profile_date);
        $this->assertResponseStatus(400);
        $json = json_decode($response->original, true);
        $this->assertEquals('error', $json['status']);
    }
}