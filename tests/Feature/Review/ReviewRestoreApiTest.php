<?php

namespace Tests\Feature\Review;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReviewRestoreApiTest extends TestCase
{
    use DatabaseMigrations;

    use DatabaseTransactions;

    /**
     * Успешное восстановление рецензии
     *
     * @return void
     * @throws \Exception
     */
    public function testReviewRestoreSuccess()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var User $alter_user */
        $alter_user = factory(User::class)->create();

        /** @var Book $book*/
        $book = $alter_user->books()->save(factory(Book::class)->make(['status' => Book::STATUS_OPEN]));

        /** @var Review $review*/
        $review = $user->reviews()->save(factory(Review::class)->make([
            'book_id' => $book->id
        ]));
        $review->delete();

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->put(route('api.review.restore', ['book_id' => $review->book_id]), [], $headers);
        $response->assertJson([
            'success' => true
        ]);
        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'deleted_at' => null
        ]);
    }

    /**
     * Провал восстановления рецензии: есть активная рецензия, нет удаленных
     *
     * @return void
     * @throws \Exception
     */
    public function testActiveExistDeletedNotExistFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var User $alter_user */
        $alter_user = factory(User::class)->create();

        /** @var Book $book*/
        $book = $alter_user->books()->save(factory(Book::class)->make(['status' => Book::STATUS_OPEN]));

        /** @var Review $review*/
        $review = $user->reviews()->save(factory(Review::class)->make([
            'book_id' => $book->id
        ]));

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->put(route('api.review.restore', ['book_id' => $review->book_id]), [], $headers);
        $response->assertJson([
            'success' => false
        ]);
    }

    /**
     * Провал восстановления рецензии: есть активная рецензия, есть удаленные
     *
     * @return void
     * @throws \Exception
     */
    public function testActiveExistDeletedExistFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var User $alter_user */
        $alter_user = factory(User::class)->create();

        /** @var Book $book*/
        $book = $alter_user->books()->save(factory(Book::class)->make(['status' => Book::STATUS_OPEN]));

        /** @var Review $review_deleted*/
        $review_deleted = $user->reviews()->save(factory(Review::class)->make([
            'book_id' => $book->id,
            'user_id' => $user->id
        ]));
        $review_deleted->delete();

        $user->reviews()->save(factory(Review::class)->make([
            'book_id' => $book->id
        ]));

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->put(route('api.review.restore', ['book_id' => $review_deleted->book_id]), [], $headers);
        $response->assertJson([
            'success' => false
        ]);
    }

    /**
     * Провал восстановления рецензии: нет рецензий совсем
     *
     * @return void
     * @throws \Exception
     */
    public function testReviewsNotExistFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var User $alter_user */
        $alter_user = factory(User::class)->create();

        /** @var Book $book*/
        $book = $alter_user->books()->save(factory(Book::class)->make(['status' => Book::STATUS_OPEN]));

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->put(route('api.review.restore', ['book_id' => $book->id]), [], $headers);
        $response->assertJson([
            'success' => false
        ]);
    }
}

