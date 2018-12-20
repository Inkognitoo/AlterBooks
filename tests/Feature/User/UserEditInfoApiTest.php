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
}

