<?php

namespace Tests\Unit\Registration;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegistrationApiTest extends TestCase
{
    use DatabaseMigrations;

    use DatabaseTransactions;

    /**
     * Проверка успешной регистрации
     *
     * @return void
     */
    public function testSuccessRegistration()
    {
        $headers = [];
        $url = 'api.registration.validate';

        $requests = [
            'email' => 'stupidfox@mail.ru',
            'password' => '123456',
            'nickname' => 'AngryFox'
        ];

        foreach ($requests as $name=>$value) {
            $response = $this->post(route($url, []), [$name, $value], $headers);
            $response->assertJson([
                'success' => true
            ]);
        }
    }
}
