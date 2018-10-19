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
        $url = 'api.registration.validate';

        $name = 'email';
        $value = 'notsostupidfox@mail.ru';

        $params = [
            'name' => $name,
            'value' => $value
        ];

        $response = $this->post(route($url), $params);
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
        $url = 'api.registration.validate';

        $name = 'email';
        $value = 'notsostupidfox';

        $params = [
            'name' => $name,
            'value' => $value
        ];

        $response = $this->post(route($url), $params);
        $response->assertJson([
            'success' => false
        ]);
    }

    /**
     * Проверка успешной валидации пароля
     *
     */
    public function testPasswordSuccess()
    {
        $url = 'api.registration.validate';

        $name = 'password';
        $value = '123456';

        $params = [
            'name' => $name,
            'value' => $value
        ];

        $response = $this->post(route($url), $params);
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
        $url = 'api.registration.validate';

        $name = 'password';
        $value = '123';

        $params = [
            'name' => $name,
            'value' => $value
        ];

        $response = $this->post(route($url), $params);
        $response->assertJson([
            'success' => false
        ]);
    }

    /**
 * Проверка успешной валидации ника
 *
 */
    public function testNicknameSuccess()
    {
        $url = 'api.registration.validate';

        $name = 'nickname';
        $value = 'AngryFox';

        $params = [
            'name' => $name,
            'value' => $value
        ];

        $response = $this->post(route($url), $params);
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

        $name = 'nickname';
        $value = $person->nickname;

        $params = [
            'name' => $name,
            'value' => $value
        ];

        $response = $this->post(route($url), $params);
        $response->assertJson([
            'success' => false
        ]);
    }
}
