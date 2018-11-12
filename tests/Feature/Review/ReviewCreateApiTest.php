<?php

namespace Tests\Feature\Review;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use ReviewCreateTestSeeder;

class ReviewCreateApiTest extends TestCase
{
    use DatabaseMigrations;

    use DatabaseTransactions;

    use WithFaker;

    public function setUp()
    {
        parent::setUp();

        $this->seed(ReviewCreateTestSeeder::class);
    }

    /**
     * Успешное создание рецензии
     *
     * @return void
     * @throws \Exception
     */
    public function testReviewCreateSuccess()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var Book $book*/
        $book = Book::inRandomOrder()->first();

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post(route('api.review.create', ['book_id' => $book->id]), ['rating' => 2, 'header' => 'header', 'text' => 'review text'], $headers);
        $response->assertJson([
            'success' => true
        ]);
        $this->assertDatabaseHas('reviews', [
            'rating' => 2,
            'header' => 'header',
            'text' => 'review text'
        ]);
    }

    /**
     * Провал создания рецензии: книга принадлежит пользователю
     *
     * @return void
     * @throws \Exception
     */
    public function testBookBelongsToUserFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var Book $book*/
        $book = $user->books()->save(factory(Book::class)->make(['status' => Book::STATUS_OPEN]));

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post(route('api.review.create', ['book_id' => $book->id]), ['rating' => 2, 'header' => 'header', 'text' => 'review text'], $headers);
        $response->assertStatus(403);
    }

    /**
     * Провал создания рецензии: существует активная рецензия
     *
     * @return void
     * @throws \Exception
     */
    public function testActiveReviewAlreadyExistFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var Book $book*/
        $book = Book::inRandomOrder()->first();

        /** @var Review $review*/
        $review = $user->reviews()->save(factory(Review::class)->make(['book_id' => $book->id]));

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post(route('api.review.create', ['book_id' => $book->id]), ['rating' => 2, 'header' => 'header', 'text' => 'review text'], $headers);
        $response->assertJson([
            'success' => false
        ]);
    }
}

