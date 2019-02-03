<?php

namespace Tests\Feature\User;

use App\Models\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserEditEmailApiTest extends TestCase
{
    use DatabaseMigrations;

    use DatabaseTransactions;

    use WithFaker;

    /**
     * Успешное редактирование данных пользователя для авторизации
     *
     * @return void
     * @throws \Exception
     */
    public function testUserEmailEditSuccess()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $old_password = '123456';
        $user->setPasswordAttribute($old_password);
        $user->save();

        $email = mb_strlen($user->nickname) < 255 ? 'a'.$user->email : 'a'.mb_substr($user->email, 1, 255);
        $password = '111111';


        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post(
            route('api.user.edit.email'),
            [
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $password,
                'old_password' => $old_password
            ],
            $headers
        );

        $user->refresh();

        $response->assertJson([
            'success' => true
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $email
        ]);

        $this->assertTrue(Hash::check($password, $user->password));
    }

    /**
     * Провал редактирования данных пользователя для авторизации: не указан электронная почта
     *
     * @return void
     * @throws \Exception
     */
    public function testNoEmailFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $old_password = '123456';
        $user->setPasswordAttribute($old_password);
        $user->save();

        $email = '';

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post(
            route('api.user.edit.email'),
            [
                'email' => $email,
                'old_password' => $old_password
            ],
            $headers
        );

        $user->refresh();

        $response->assertJson([
            'success' => false
        ]);
    }

    /**
     * Провал редактирования данных пользователя для авторизации: указан не электронная почта
     *
     * @return void
     * @throws \Exception
     */
    public function testNotEmailFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $old_password = '123456';
        $user->setPasswordAttribute($old_password);
        $user->save();

        $email = '123';

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post(
            route('api.user.edit.email'),
            [
                'email' => $email,
                'old_password' => $old_password
            ],
            $headers
        );

        $user->refresh();

        $response->assertJson([
            'success' => false
        ]);
    }

    /**
     * Провал редактирования данных пользователя для авторизации: электронная почта слишком длинная
     *
     * @return void
     * @throws \Exception
     */
    public function testEmailMoreMaxFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $old_password = '123456';
        $user->setPasswordAttribute($old_password);
        $user->save();

        $email = mb_convert_encoding(str_random(400), 'UTF-8') . '@mail.ru';

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post(
            route('api.user.edit.email'),
            [
                'email' => $email,
                'old_password' => $old_password
            ],
            $headers
        );

        $user->refresh();

        $response->assertJson([
            'success' => false
        ]);
    }

    /**
     * Провал редактирования данных пользователя для авторизации: указанная электронная почта уже существует
     *
     * @return void
     * @throws \Exception
     */
    public function testEmailNotUniqueFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $old_password = '123456';
        $user->setPasswordAttribute($old_password);
        $user->save();

        /** @var User $atler_user */
        $alter_user = factory(User::class)->create();

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post(
            route('api.user.edit.email'),
            [
                'email' => $alter_user->email,
                'old_password' => $old_password
            ],
            $headers
        );

        $user->refresh();

        $response->assertJson([
            'success' => false
        ]);
    }

    /**
     * Провал редактирования данных пользователя для авторизации: пароль слишком короткий
     *
     * @return void
     * @throws \Exception
     */
    public function testPasswordLessMinFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $old_password = '123456';
        $user->setPasswordAttribute($old_password);
        $user->save();

        $password = '1';

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post(
            route('api.user.edit.email'),
            [
                'email' => $user->email,
                'password' => $password,
                'password_confirmation' => $password,
                'old_password' => $old_password
            ],
            $headers
        );

        $user->refresh();

        $response->assertJson([
            'success' => false
        ]);
    }

    /**
     * Провал редактирования данных пользователя для авторизации: пароль не подтвержден
     *
     * @return void
     * @throws \Exception
     */
    public function testPasswordNotConfirmFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $old_password = '123456';
        $user->setPasswordAttribute($old_password);
        $user->save();

        $password = '111111';

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post(
            route('api.user.edit.email'),
            [
                'email' => $user->email,
                'password' => $password,
                'password_confirmation' => '1111111',
                'old_password' => $old_password
            ],
            $headers
        );

        $user->refresh();

        $response->assertJson([
            'success' => false
        ]);
    }

    /**
     * Провал редактирования данных пользователя для авторизации: не введен текущий пароль для изменения данных авторизации
     *
     * @return void
     * @throws \Exception
     */
    public function testNoCurrentPasswordFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $password = '111111';

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post(
            route('api.user.edit.email'),
            [
                'email' => $user->email,
                'password' => $password,
                'password_confirmation' => $password,
                'old_password' => ''
            ],
            $headers
        );

        $user->refresh();

        $response->assertJson([
            'success' => false
        ]);
    }

    /**
     * Провал редактирования данных пользователя для авторизации: текущий пароль введен неверно для изменения данных авторизации
     *
     * @return void
     * @throws \Exception
     */
    public function testNotCurrentPasswordFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $old_password = '123456';
        $password = '111111';

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post(
            route('api.user.edit.email'),
            [
                'email' => $user->email,
                'password' => $password,
                'password_confirmation' => $password,
                'old_password' => $old_password
            ],
            $headers
        );

        $user->refresh();

        $response->assertJson([
            'success' => false
        ]);
    }
}

