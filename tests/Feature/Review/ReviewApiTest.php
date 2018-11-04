<?php

namespace Tests\Feature\Review;

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
     * НЕ ЗНАКОНЧЕНА
     *
     * @return void
     */
    public function testAddFirstReview()
    {
//        $this->seed(ReviewTestSeeder::class);
//
//        /** @var User $person */
//        $person = factory(User::class)->create();
//        $review = Review::inRandomOrder()
//            ->first()
//        ;
//
//        $headers = [
//            'Authorization' => 'Bearer ' . $person->api_token
//        ];
        $this->assertTrue(true);
    }
}
