<?php

namespace Tests\Unit\Library;

use App\Book;
use App\Review;
use App\ReviewEstimate;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use ReviewEstimateTestSeeder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReviewPositiveEstimateTest extends TestCase
{
    use DatabaseMigrations;

    use DatabaseTransactions;

    /**
     * Проверяем успешную позитивную оценку
     *
     * @return void
     */
    public function testPositiveEstimate()
    {
        $this->seed(ReviewEstimateTestSeeder::class);

        /** @var User $person */
        $person = factory(User::class)->create();
        $review = Review::inRandomOrder()
            ->first()
        ;

        $headers = [
            'Authorization' => 'Bearer ' . $person->api_token
        ];

        $response = $this->post(route('api.review.estimate.plus', ['id' => $review->id, 'book_id' => $review->book_id]), [], $headers);
        $response->assertJson([
            'success' => true,
            'code' => 200,
            'data' => [
                'estimate' => 1
            ]
        ]);
        $this->assertDatabaseHas('review_estimates', [
            'user_id' => $person->id,
            'review_id' => $review->id,
            'estimate' => 1
        ]);
    }

    /**
     * Проверяем повторную позитивную оценку
     *
     * @return void
     */
    public function testPositiveEstimateAgain()
    {
        $this->seed(ReviewEstimateTestSeeder::class);

        /** @var User $person */
        $person = factory(User::class)->create();
        $review = Review::inRandomOrder()
            ->first()
        ;

        $headers = [
            'Authorization' => 'Bearer ' . $person->api_token
        ];

        $response = $this->post(route('api.review.estimate.plus', ['id' => $review->id, 'book_id' => $review->book_id]), [], $headers);
        $response->assertJson([
            'success' => true,
            'code' => 200,
            'data' => [
                'estimate' => 1
            ]
        ]);
        $this->assertDatabaseHas('review_estimates', [
            'user_id' => $person->id,
            'review_id' => $review->id,
            'estimate' => 1
        ]);

        $response = $this->post(route('api.review.estimate.plus', ['id' => $review->id, 'book_id' => $review->book_id]), [], $headers);
        $response->assertJson([
            'success' => false,
            'code' => 400
        ]);
        $this->assertDatabaseHas('review_estimates', [
            'user_id' => $person->id,
            'review_id' => $review->id,
            'estimate' => 1
        ]);
    }

    /**
     * Проверяем добавление нескольких позитивных оценок подряд
     *
     * @return void
     */
    public function testMultiplePositiveEstimate()
    {
        $this->seed(ReviewEstimateTestSeeder::class);

        /** @var User $person */
        $person = factory(User::class)->create();
        $reviews = Review::inRandomOrder()
            ->limit(10)
            ->get()
        ;

        $headers = [
            'Authorization' => 'Bearer ' . $person->api_token
        ];

        foreach ($reviews as $review) {
            $response = $this->post(route('api.review.estimate.plus', ['id' => $review->id, 'book_id' => $review->book_id]), [], $headers);
            $response->assertJson([
                'success' => true,
                'code' => 200,
                'data' => [
                    'estimate' => 1
                ]
            ]);
            $this->assertDatabaseHas('review_estimates', [
                'user_id' => $person->id,
                'review_id' => $review->id,
                'estimate' => 1
            ]);

            $response = $this->post(route('api.review.estimate.plus', ['id' => $review->id, 'book_id' => $review->book_id]), [], $headers);
            $response->assertJson([
                'success' => false,
                'code' => 400
            ]);
            $this->assertDatabaseHas('review_estimates', [
                'user_id' => $person->id,
                'review_id' => $review->id,
                'estimate' => 1
            ]);

            usleep(500000);
        }
    }


    /**
     * Проверяем невозможность позитивной оценки собственной рецензии
     *
     * @return void
     */
    public function testPositiveEstimateOwnReview()
    {
        $this->seed(ReviewEstimateTestSeeder::class);

        /** @var User $person */
        $person = factory(User::class, 1)->create()->each(function ($u) {
            $count = 1;
            for ($i = 0; $i < $count; $i++) {
                $book = Book::inRandomOrder()->where('author_id', '!=', $u->id)->first();
                if (filled($book)) {
                    $u->reviews()->save(factory(Review::class)->make([
                        'book_id' => $book->id,
                        'user_id' => $u->id
                    ]));
                }
            }
        })->first();
        $review = $person->reviews->first();

        $headers = [
            'Authorization' => 'Bearer ' . $person->api_token
        ];

        $response = $this->post(route('api.review.estimate.plus', ['id' => $review->id, 'book_id' => $review->book_id]), [], $headers);
        $response->assertJson([
            'success' => false,
            'code' => 403
        ]);
        $this->assertDatabaseMissing('review_estimates', [
            'user_id' => $person->id,
            'review_id' => $review->id,
        ]);
    }


    /**
     * Проверяем невозможность позитивной оценки рецензии собственной книги
     *
     * @return void
     */
    public function testPositiveEstimateOwnBookReview()
    {
        $this->seed(ReviewEstimateTestSeeder::class);

        /** @var User $person */
        $person = factory(User::class, 10)->create()->each(function ($u) {
            $count = 1;
            for ($i = 0; $i < $count; $i++) {
                $u->books()->save(factory(Book::class)->make(['status' => Book::STATUS_OPEN]));
            }
        })->first();
        $book = $person->books->first();
        $user = User::inRandomOrder()->where('id', '!=', $person->id)->first();
        /** @var Review $review */
        $review = factory(Review::class)->make(['book_id' => $book->id, 'user_id' => $user->id]);
        $review->save();

        $headers = [
            'Authorization' => 'Bearer ' . $person->api_token
        ];

        $response = $this->post(route('api.review.estimate.plus', ['id' => $review->id, 'book_id' => $review->book_id]), [], $headers);
        $response->assertJson([
            'success' => false,
            'code' => 403
        ]);
        $this->assertDatabaseMissing('review_estimates', [
            'user_id' => $person->id,
            'review_id' => $review->id,
        ]);
    }

    /**
     * Проверяем успешное обнуление позитивной оценки
     *
     * @return void
     */
    public function testDeletePositiveEstimate()
    {
        $this->seed(ReviewEstimateTestSeeder::class);

        /** @var User $person */
        $person = factory(User::class)->create();
        $review = Review::inRandomOrder()
            ->first()
        ;

        $headers = [
            'Authorization' => 'Bearer ' . $person->api_token
        ];

        $review_estimate = new ReviewEstimate();
        $review_estimate->user_id = $person->id;
        $review_estimate->review_id = $review->id;
        $review_estimate->estimate = 1;
        $review_estimate->save();

        $this->assertDatabaseHas('review_estimates', [
            'user_id' => $person->id,
            'review_id' => $review->id,
            'estimate' => 1
        ]);

        $response = $this->post(route('api.review.estimate.minus', ['id' => $review->id, 'book_id' => $review->book_id]), [], $headers);
        $response->assertJson([
            'success' => true,
            'code' => 200,
            'data' => [
                'estimate' => 0
            ]
        ]);
        $this->assertDatabaseHas('review_estimates', [
            'user_id' => $person->id,
            'review_id' => $review->id,
            'estimate' => 0
        ]);
    }

    /**
     * Проверяем успешное превращение негативной оценки в позитивную
     *
     * @return void
     */
    public function testPositiveToNegativeEstimate()
    {
        $this->seed(ReviewEstimateTestSeeder::class);

        /** @var User $person */
        $person = factory(User::class)->create();
        $review = Review::inRandomOrder()
            ->first()
        ;

        $headers = [
            'Authorization' => 'Bearer ' . $person->api_token
        ];

        $review_estimate = new ReviewEstimate();
        $review_estimate->user_id = $person->id;
        $review_estimate->review_id = $review->id;
        $review_estimate->estimate = -1;
        $review_estimate->save();

        $this->assertDatabaseHas('review_estimates', [
            'user_id' => $person->id,
            'review_id' => $review->id,
            'estimate' => -1
        ]);

        $response = $this->post(route('api.review.estimate.plus', ['id' => $review->id, 'book_id' => $review->book_id]), [], $headers);
        $response->assertJson([
            'success' => true,
            'code' => 200,
            'data' => [
                'estimate' => 0
            ]
        ]);
        $response = $this->post(route('api.review.estimate.plus', ['id' => $review->id, 'book_id' => $review->book_id]), [], $headers);
        $response->assertJson([
            'success' => true,
            'code' => 200,
            'data' => [
                'estimate' => 1
            ]
        ]);
        $this->assertDatabaseHas('review_estimates', [
            'user_id' => $person->id,
            'review_id' => $review->id,
            'estimate' => 1
        ]);
    }
}
