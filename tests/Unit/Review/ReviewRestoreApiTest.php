<?php

namespace Tests\Unit\Review;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use ReviewRestoreTestSeeder;

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
    public function testReviewRestoreSuccess() {
        $this->seed(ReviewRestoreTestSeeder::class);

        /** @var User $user */
        $user = User::inRandomOrder()->first();

        do {
            $alter_user = User::inRandomOrder()->first();
        } while ($user === $alter_user);

        $book = $alter_user->books()->save(factory(Book::class)->make(['status' => \App\Models\Book::STATUS_OPEN]));

        $review = $user->reviews()->save(factory(Review::class)->make([
            'book_id' => $book->id,
            'user_id' => $user->id
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
    public function testActiveExistDeletedNotExistFail() {
        $this->seed(ReviewRestoreTestSeeder::class);

        /** @var User $user */
        $user = User::inRandomOrder()->first();

        do {
            $alter_user = User::inRandomOrder()->first();
        } while ($user === $alter_user);

        $book = $alter_user->books()->save(factory(Book::class)->make(['status' => \App\Models\Book::STATUS_OPEN]));

        $review = $user->reviews()->save(factory(Review::class)->make([
            'book_id' => $book->id,
            'user_id' => $user->id
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
    public function testActiveExistDeletedExistFail() {
        $this->seed(ReviewRestoreTestSeeder::class);

        /** @var User $user */
        $user = User::inRandomOrder()->first();

        do {
            $alter_user = User::inRandomOrder()->first();
        } while ($user === $alter_user);

        $book = $alter_user->books()->save(factory(Book::class)->make(['status' => \App\Models\Book::STATUS_OPEN]));

        $review_delete = $user->reviews()->save(factory(Review::class)->make([
            'book_id' => $book->id,
            'user_id' => $user->id
        ]));
        $review_delete->delete();

        $review = $user->reviews()->save(factory(Review::class)->make([
            'book_id' => $book->id,
            'user_id' => $user->id
        ]));

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->put(route('api.review.restore', ['book_id' => $review_delete->book_id]), [], $headers);
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
    public function testReviewsNotExistFail() {
        $this->seed(ReviewRestoreTestSeeder::class);

        /** @var User $user */
        $user = User::inRandomOrder()->first();

        do {
            $alter_user = User::inRandomOrder()->first();
        } while ($user === $alter_user);

        $book = $alter_user->books()->save(factory(Book::class)->make(['status' => \App\Models\Book::STATUS_OPEN]));

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->put(route('api.review.restore', ['book_id' => $book->id]), [], $headers);
        $response->assertJson([
            'success' => false
        ]);
    }
}

