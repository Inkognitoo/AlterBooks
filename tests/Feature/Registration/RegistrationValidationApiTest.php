<?php

namespace Tests\Feature\Registration;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegistrationValidationApiTest extends TestCase
{
    use DatabaseMigrations;

    use DatabaseTransactions;

    /**
     * Успешная валидация email
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
     * Провал валидации email: строка не является email
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
     * Провал валидации email: указан существующий email
     *
     */
    public function testEmaiAlreadyExistslFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $url_name = 'api.registration.validate';

        $params = [
            'email' => $user->email,
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
     * Провал валидации email: указан существующий email в другом регистре
     *
     */
    public function testEmailExistsCaseFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $url_name = 'api.registration.validate';

        $email = $user->email;
        $email{0} = ctype_upper($email{0}) ?
            mb_convert_case($email{0}, MB_CASE_LOWER, "UTF-8") :
            mb_convert_case($email{0}, MB_CASE_UPPER, "UTF-8");

        $params = [
            'email' => $email,
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
     * Успешная валидация пароля
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
     * Провал валидации пароля: менее 6 символов
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
     * Успешная валидация никнейма
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
     * Провал валидации никнейма: указан существующий никнейм
     *
     */
    public function testNicknameFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $url = 'api.registration.validate';

        $params = [
            'nickname' => $user->nickname,
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

    /**
     * Провал валидации никнейма: указан существующий никнейм
     *
     */
    public function testNicknameCaseFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $url = 'api.registration.validate';

        $nickname = $user->nickname;
        $nickname{0} = ctype_upper($nickname{0}) ?
            mb_convert_case($nickname{0}, MB_CASE_LOWER, "UTF-8") :
            mb_convert_case($nickname{0}, MB_CASE_UPPER, "UTF-8");

        $params = [
            'nickname' => $nickname,
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
