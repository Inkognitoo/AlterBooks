<?php

namespace Tests\Feature\Review;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserEditInfoApiTest extends TestCase
{
    use DatabaseMigrations;

    use DatabaseTransactions;

    use WithFaker;

    /**
     * Успешное редактирование данных пользователя
     *
     * @return void
     * @throws \Exception
     */
    public function testReviewEditSuccess()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $nickname = mb_strlen($user->nickname) < 29 ? $user->nickname.'1' : mb_substr($user->nickname, 0, 20);

        do {
            $name = mb_convert_encoding($this->faker->realText(random_int(10, 29)), 'UTF-8');
        } while ($name === $user->name);

        do {
            $surname = mb_convert_encoding($this->faker->realText(random_int(10, 29)), 'UTF-8');
        } while ($surname === $user->surname);

        do {
            $patronymic = mb_convert_encoding($this->faker->realText(random_int(10, 29)), 'UTF-8');
        } while ($patronymic === $user->patronymic);

        do {
            $about = mb_convert_encoding($this->faker->realText(random_int(200, 300)), 'UTF-8');
        } while ($about === $user->about);

        $birthdayDate = Carbon::now()->format('Y-m-d');

        $gender = $user->gender === 'n' ? 'f' : 'n';

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post
        (
            route('api.user.edit.info', []),
            [
                'nickname' => $nickname,
                'name' => $name,
                'surname' => $surname,
                'patronymic' => $patronymic,
                'birthday_date' => $birthdayDate,
                'gender' => $gender,
                'about' => $about
            ],
            $headers
        );

        $response->assertJson([
            'success' => true
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'nickname' => $nickname,
            'name' => $name,
            'surname' => $surname,
            'patronymic' => $patronymic,
            'birthday_date' => $birthdayDate,
            'gender' => $gender,
            'about' => $about
        ]);
    }

    /**
     * Провал редактирования личных данных пользователя: никнейм не введен
     *
     * @return void
     * @throws \Exception
     */
    public function testNoNicknameFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post
        (
            route('api.user.edit.info', []),
            [
                'nickname' => '',
                'gender' => $user->gender
            ],
            $headers
        );

        $response->assertJson([
            'success' => false
        ]);
    }

    /**
     * Провал редактирования личных данных пользователя: введенный никнейм уже существует
     *
     * @return void
     * @throws \Exception
     */
    public function testNicknameAlreadyExistFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var User $user */
        $alterUser = factory(User::class)->create();

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post
        (
            route('api.user.edit.info', []),
            [
                'nickname' => $alterUser->nickname,
                'gender' => $user->gender
            ],
            $headers
        );

        $response->assertJson([
            'success' => false
        ]);
    }

    /**
     * Провал редактирования личных данных пользователя: никнейм длиннее 30 символов
     *
     * @return void
     * @throws \Exception
     */
    public function testNicknameMoreMaxFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $nickname = '1234567890123456789012345678901234567890';

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post
        (
            route('api.user.edit.info', []),
            [
                'nickname' => $nickname,
                'gender' => $user->gender
            ],
            $headers
        );

        $response->assertJson([
            'success' => false
        ]);
    }

    /**
     * Провал редактирования личных данных пользователя: никнейм содержит запрещенные символы
     *
     * @return void
     * @throws \Exception
     */
    public function testNicknameInvalidFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $nickname = 'Лисааааааа';

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post
        (
            route('api.user.edit.info', []),
            [
                'nickname' => $nickname,
                'gender' => $user->gender
            ],
            $headers
        );

        $response->assertJson([
            'success' => false
        ]);
    }

    /**
     * Провал редактирования личных данных пользователя: имя длиннее максимального
     *
     * @return void
     * @throws \Exception
     */
    public function testNameMoreMaxFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $name = '1234567890123456789012345678901234567890';

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post
        (
            route('api.user.edit.info', []),
            [
                'nickname' => $user->nickname,
                'name' => $name,
                'gender' => $user->gender
            ],
            $headers
        );

        $response->assertJson([
            'success' => false
        ]);
    }

    /**
     * Провал редактирования личных данных пользователя: фамилия длиннее максимального
     *
     * @return void
     * @throws \Exception
     */
    public function testSurnameMoreMaxFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $surname = '1234567890123456789012345678901234567890';

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post
        (
            route('api.user.edit.info', []),
            [
                'nickname' => $user->nickname,
                'surname' => $surname,
                'gender' => $user->gender
            ],
            $headers
        );

        $response->assertJson([
            'success' => false
        ]);
    }

    /**
     * Провал редактирования личных данных пользователя: отчество длиннее максимального
     *
     * @return void
     * @throws \Exception
     */
    public function testPatronymicMoreMaxFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $patronymic = '1234567890123456789012345678901234567890';

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post
        (
            route('api.user.edit.info', []),
            [
                'nickname' => $user->nickname,
                'patronymic' => $patronymic,
                'gender' => $user->gender
            ],
            $headers
        );

        $response->assertJson([
            'success' => false
        ]);
    }

    /**
     * Провал редактирования личных данных пользователя: пол не указан
     *
     * @return void
     * @throws \Exception
     */
    public function testNoGenderFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post
        (
            route('api.user.edit.info', []),
            [
                'nickname' => $user->nickname,
                'gender' => ''
            ],
            $headers
        );

        $response->assertJson([
            'success' => false
        ]);
    }

    /**
     * Провал редактирования личных данных пользователя: пол указан неизвестной строкой
     *
     * @return void
     * @throws \Exception
     */
    public function testGenderUnknownFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $gender = '???';

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post
        (
            route('api.user.edit.info', []),
            [
                'nickname' => $user->nickname,
                'gender' => $gender
            ],
            $headers
        );

        $response->assertJson([
            'success' => false
        ]);
    }

    /**
     * Провал редактирования личных данных пользователя: дата рождения не имеет формат даты
     *
     * @return void
     * @throws \Exception
     */
    public function testDateUnknownFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $date = '???';

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post
        (
            route('api.user.edit.info', []),
            [
                'nickname' => $user->nickname,
                'birthday-date' => $date,
                'gender' => $user->gender
            ],
            $headers
        );

        $response->assertJson([
            'success' => false
        ]);
    }

        /**
         * Провал редактирования личных данных пользователя: значение "О себе" длиннее максимального
         *
         * @return void
         * @throws \Exception
         */
        public function testAboutMoreMaxFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $about = '11111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111';

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post
        (
            route('api.user.edit.info', []),
            [
                'nickname' => $user->nickname,
                'gender' => $user->gender,
                'about' => $about
            ],
            $headers
        );

        $response->assertJson([
            'success' => false
        ]);
    }
}

