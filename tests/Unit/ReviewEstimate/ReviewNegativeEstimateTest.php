<?php

namespace Tests\Unit\Library;

use App\Models\Book;
use App\Models\Review;
use App\Models\ReviewEstimate;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use ReviewEstimateTestSeeder;
use Tests\TestCase;

class ReviewNegativeEstimateTest extends TestCase
{
    use DatabaseMigrations;

    use DatabaseTransactions;

    /**
     * Проверяем успешную негативную оценку
     *
     * @return void
     */
    public function testNegativeEstimate()
    {
        $this->seed(ReviewEstimateTestSeeder::class);

        /** @var User $user */
        $user = factory(User::class)->create();
        $review = Review::inRandomOrder()
            ->first()
        ;

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post(route('api.review.estimate.minus', ['id' => $review->id, 'book_id' => $review->book_id]), [], $headers);
        $response->assertJson([
            'success' => true,
            'data' => [
                'estimate' => -1
            ]
        ]);
        $this->assertDatabaseHas('review_estimates', [
            'user_id' => $user->id,
            'review_id' => $review->id,
            'estimate' => -1
        ]);
    }

    /**
     * Проверяем повторную негативную оценку
     *
     * @return void
     */
    public function testNegativeEstimateAgain()
    {
        $this->seed(ReviewEstimateTestSeeder::class);

        /** @var User $user */
        $user = factory(User::class)->create();
        $review = Review::inRandomOrder()
            ->first()
        ;

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post(route('api.review.estimate.minus', ['id' => $review->id, 'book_id' => $review->book_id]), [], $headers);
        $response->assertJson([
            'success' => true,
            'data' => [
                'estimate' => -1
            ]
        ]);
        $this->assertDatabaseHas('review_estimates', [
            'user_id' => $user->id,
            'review_id' => $review->id,
            'estimate' => -1
        ]);

        $response = $this->post(route('api.review.estimate.minus', ['id' => $review->id, 'book_id' => $review->book_id]), [], $headers);
        $response->assertJson([
            'success' => false,
        ]);
        $this->assertDatabaseHas('review_estimates', [
            'user_id' => $user->id,
            'review_id' => $review->id,
            'estimate' => -1
        ]);
    }

    /**
     * Проверяем добавление нескольких негативных оценок подряд
     *
     * @return void
     */
    public function testMultipleNegativeEstimate()
    {
        $this->seed(ReviewEstimateTestSeeder::class);

        /** @var User $user */
        $user = factory(User::class)->create();
        $reviews = Review::inRandomOrder()
            ->limit(10)
            ->get()
        ;

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        foreach ($reviews as $review) {
            $response = $this->post(route('api.review.estimate.minus', ['id' => $review->id, 'book_id' => $review->book_id]), [], $headers);
            $response->assertJson([
                'success' => true,
                'data' => [
                    'estimate' => -1
                ]
            ]);
            $this->assertDatabaseHas('review_estimates', [
                'user_id' => $user->id,
                'review_id' => $review->id,
                'estimate' => -1
            ]);

            $response = $this->post(route('api.review.estimate.minus', ['id' => $review->id, 'book_id' => $review->book_id]), [], $headers);
            $response->assertJson([
                'success' => false,
            ]);
            $this->assertDatabaseHas('review_estimates', [
                'user_id' => $user->id,
                'review_id' => $review->id,
                'estimate' => -1
            ]);
        }
    }

    /**
     * Проверяем невозможность негативной оценки собственной рецензии
     *
     * @return void
     */
    public function testNegativeEstimateOwnReview()
    {
        $this->seed(ReviewEstimateTestSeeder::class);

        /** @var User $user */
        $user = factory(User::class, 1)->create()->each(function ($user) {
            /** @var User $user */
            $count = 1;
            for ($i = 0; $i < $count; $i++) {
                $book = Book::inRandomOrder()->where('author_id', '!=', $user->id)->first();
                if (filled($book)) {
                    $user->reviews()->save(factory(Review::class)->make([
                        'book_id' => $book->id,
                        'user_id' => $user->id
                    ]));
                }
            }
        })->first();
        $review = $user->reviews->first();

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post(route('api.review.estimate.minus', ['id' => $review->id, 'book_id' => $review->book_id]), [], $headers);
        $response->assertJson([
            'success' => false,
        ]);
        $this->assertDatabaseMissing('review_estimates', [
            'user_id' => $user->id,
            'review_id' => $review->id,
        ]);
    }

    /**
     * Проверяем невозможность негативной оценки рецензии собственной книги
     *
     * @return void
     */
    public function testNegativeEstimateOwnBookReview()
    {
        $this->seed(ReviewEstimateTestSeeder::class);

        /** @var User $person */
        $person = factory(User::class, 10)->create()->each(function ($user) {
            /** @var User $user */
            $count = 1;
            for ($i = 0; $i < $count; $i++) {
                $user->books()->save(factory(Book::class)->make(['status' => Book::STATUS_OPEN]));
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

        $response = $this->post(route('api.review.estimate.minus', ['id' => $review->id, 'book_id' => $review->book_id]), [], $headers);
        $response->assertJson([
            'success' => false,
        ]);
        $this->assertDatabaseMissing('review_estimates', [
            'user_id' => $person->id,
            'review_id' => $review->id,
        ]);
    }

    /**
     * Проверяем успешное обнуление негативной оценки
     *
     * @return void
     */
    public function testDeleteNegativeEstimate()
    {
        $this->seed(ReviewEstimateTestSeeder::class);

        /** @var User $user */
        $user = factory(User::class)->create();
        $review = Review::inRandomOrder()
            ->first()
        ;

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $review_estimate = new ReviewEstimate();
        $review_estimate->user_id = $user->id;
        $review_estimate->review_id = $review->id;
        $review_estimate->estimate = -1;
        $review_estimate->save();

        $this->assertDatabaseHas('review_estimates', [
            'user_id' => $user->id,
            'review_id' => $review->id,
            'estimate' => -1
        ]);

        $response = $this->post(route('api.review.estimate.plus', ['id' => $review->id, 'book_id' => $review->book_id]), [], $headers);
        $response->assertJson([
            'success' => true,
            'data' => [
                'estimate' => 0
            ]
        ]);
        $this->assertDatabaseHas('review_estimates', [
            'user_id' => $user->id,
            'review_id' => $review->id,
            'estimate' => 0
        ]);
    }

    /**
     * Проверяем успешное превращение позитивной оценки в негативную
     *
     * @return void
     */
    public function testPositiveToNegativeEstimate()
    {
        $this->seed(ReviewEstimateTestSeeder::class);

        /** @var User $user */
        $user = factory(User::class)->create();
        $review = Review::inRandomOrder()
            ->first()
        ;

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $review_estimate = new ReviewEstimate();
        $review_estimate->user_id = $user->id;
        $review_estimate->review_id = $review->id;
        $review_estimate->estimate = 1;
        $review_estimate->save();

        $this->assertDatabaseHas('review_estimates', [
            'user_id' => $user->id,
            'review_id' => $review->id,
            'estimate' => 1
        ]);

        $response = $this->post(route('api.review.estimate.minus', ['id' => $review->id, 'book_id' => $review->book_id]), [], $headers);
        $response->assertJson([
            'success' => true,
            'data' => [
                'estimate' => 0
            ]
        ]);
        $response = $this->post(route('api.review.estimate.minus', ['id' => $review->id, 'book_id' => $review->book_id]), [], $headers);
        $response->assertJson([
            'success' => true,
            'data' => [
                'estimate' => -1
            ]
        ]);
        $this->assertDatabaseHas('review_estimates', [
            'user_id' => $user->id,
            'review_id' => $review->id,
            'estimate' => -1
        ]);
    }
}
