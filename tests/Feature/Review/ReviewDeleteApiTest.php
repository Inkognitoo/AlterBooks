<?php

namespace Tests\Unit\Review;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReviewDeleteApiTest extends TestCase
{
    use DatabaseMigrations;

    use DatabaseTransactions;

    /**
     * Успешное удаление рецензии
     *
     * @return void
     * @throws \Exception
     */
    public function testReviewEditSuccess()
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

        $response = $this->delete(route('api.review.delete', ['book_id' => $review->book_id, 'id' => $review->id]), [], $headers);
        $response->assertJson([
            'success' => true
        ]);
        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'deleted_at' => $review->refresh()->deleted_at
        ]);
    }
}

