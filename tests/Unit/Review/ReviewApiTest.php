<?php

namespace Tests\Unit;

use App\Models\Review;
use App\Models\User;
use Tests\TestCase;
use ReviewTestSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReviewApiTest extends TestCase
{
    use DatabaseMigrations;

    use DatabaseTransactions;

    /**
     * Авторизованный пользователь добавляет первую рецензию к книге со статусом ОПУБЛИКОВАНО (рецензий нет вообще)
     *
     * @return void
     */
    public function addFirstReview()
    {
        $this->seed(ReviewTestSeeder::class);

        /** @var User $person */
        $person = factory(User::class)->create();
        $review = Review::inRandomOrder()
            ->first()
        ;

        $headers = [
            'Authorization' => 'Bearer ' . $person->api_token
        ];
    }
}