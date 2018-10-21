<?php

namespace Tests\Unit\Registration;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegistrationValidationApiTest extends TestCase
{
    use DatabaseMigrations;

    use DatabaseTransactions;

    /**
     * Проверка успешной валидации email
     *
     */
    public function testEmailSuccess()
    {
        $url_name = 'api.registration.validate';

        $email = 'notsostupidfox@mail.ru';
        $params = [
            'email' => $email,
        ];

        $response = $this->post(route($url_name), $params);
        $response->assertJson([
            'success' => true
        ]);
    }

    /**
     * Проверка провальной валидации email
     *
     */
    public function testEmailFail()
    {
        $url_name = 'api.registration.validate';

        $email = 'notsostupidfox';
        $params = [
            'email' => $email,
        ];

        $response = $this->post(route($url_name), $params);
        $response->assertJson([
            'success' => false,
            'errors' => [
                [
                    'name' => 'email',
                    'message' => 'введённая строка не является адресом электронной почты'
                ]
            ]
        ]);
    }

    /**
     * Проверка провальной валидации уже существующего email
     *
     */
    public function testEmaiAlreadyExistslFail()
    {
        /** @var User $person */
        $person = factory(User::class)->create();

        $url_name = 'api.registration.validate';

        $params = [
            'email' => $person->email,
        ];

        $response = $this->post(route($url_name), $params);
        $response->assertJson([
            'success' => false,
            'errors' => [
                [
                    'name' => 'email',
                    'message' => 'профиль с данными адресом электронной почты уже существует'
                ]
            ]
        ]);
    }

    /**
     * Проверка успешной валидации пароля
     *
     */
    public function testPasswordSuccess()
    {
        $url_name = 'api.registration.validate';

        $password = '123456';
        $params = [
            'password' => $password,
        ];

        $response = $this->post(route($url_name), $params);
        $response->assertJson([
            'success' => true
        ]);
    }

    /**
     * Проверка провальной валидации пароля
     *
     */
    public function testPasswordFail()
    {
        $url_name = 'api.registration.validate';

        $password = '123';
        $params = [
            'password' => $password,
        ];

        $response = $this->post(route($url_name), $params);
        $response->assertJson([
            'success' => false,
            'errors' => [
                [
                    'name' => 'password',
                    'message' => 'пароль менее 6 символов'
                ]
            ]
        ]);
    }

    /**
     * Проверка успешной валидации ника
     *
     */
    public function testNicknameSuccess()
    {
        $url_name = 'api.registration.validate';

        $nickname = 'AngryFox';
        $params = [
            'nickname' => $nickname,
        ];

        $response = $this->post(route($url_name), $params);
        $response->assertJson([
            'success' => true
        ]);
    }

    /**
     * Проверка провальной валидации ника
     *
     */
    public function testNicknameFail()
    {
        /** @var User $person */
        $person = factory(User::class)->create();

        $url = 'api.registration.validate';

        $params = [
            'nickname' => $person->nickname,
        ];

        $response = $this->post(route($url), $params);
        $response->assertJson([
            'success' => false,
            'errors' => [
                [
                    'name' => 'nickname',
                    'message' => 'пользователь с данным псевдонимом уже существует'
                ]
            ]
        ]);
    }
}
