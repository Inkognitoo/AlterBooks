<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegistrationTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Пытаемся зарегистрировать нового пользователя
     *
     * @return void
     * @throws \Exception
     * @throws \Throwable
     */
    public function testRegistration()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->type('nickname', 'Inkogntioo')
                    ->type('email', 'inkognitoo92@yandex.ru')
                    ->type('password', '12345678')
                    ->type('password_confirmation', '12345678')
                    ->press('Регистрация')
                    ->assertSee('Inkogntioo')
                    ->logout();
        });
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testAuth()
    {
        /** @var User $user */
        $user = factory(User::class)->create([
            'password' => 'testPassword',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'testPassword')
                ->press('Вход')
                ->assertSee($user->full_name)
                ->logout();
        });
    }
}
