<?php

namespace Tests\Feature\Review;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReviewEditApiTest extends TestCase
{
    use DatabaseMigrations;

    use DatabaseTransactions;

    use WithFaker;

    /**
     * Успешное редактирование рецензии
     *
     * @return void
     * @throws \Exception
     */
    public function testReviewEditSuccess()
    {
        /** @var User $user */
        $user = factory(User::class)->create();;

        /** @var User $alter_user */
        $alter_user = factory(User::class)->create();

        /** @var Book $book */
        $book = $alter_user->books()->save(factory(Book::class)->make(['status' => Book::STATUS_OPEN]));

        /** @var Review $review */
        $review = $user->reviews()->save(factory(Review::class)->make([
            'book_id' => $book->id
        ]));

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        do {
            $review_rating = random_int(1, 10);
        } while ($review_rating === $review->rating);

        do {
            $review_header = mb_convert_encoding($this->faker->realText(random_int(20, 67)), 'UTF-8');
        } while ($review_header === $review->header);

        do {
            $review_text = mb_convert_encoding($this->faker->realText(random_int(100, 500)), 'UTF-8');
        } while ($review_text === $review->text);


        $response = $this->put(route('api.review.edit', ['book_id' => $review->book_id, 'id' => $review->id]), ['rating' => $review_rating, 'header' => $review_header, 'text' => $review_text], $headers);
        $response->assertJson([
            'success' => true
        ]);
        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'rating' => $review_rating,
            'header' => $review_header,
            'text' => $review_text
        ]);
    }


}

