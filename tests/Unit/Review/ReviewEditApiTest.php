<?php

namespace Tests\Unit\Review;

use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use ReviewEditTestSeeder;

class ReviewEditApiTest extends TestCase
{
    use DatabaseMigrations;

    use DatabaseTransactions;

    use WithFaker;

    /**
     * Успешное редактирование рецензии
     *
     * @return void
    */
    public function testReviewEditSuccess() {
        $this->seed(ReviewEditTestSeeder::class);

        /** @var User $person */
        $person = User::inRandomOrder()->first();
        $review = $person->reviews()->inRandomOrder()->first();

        $headers = [
            'Authorization' => 'Bearer ' . $person->api_token
        ];

        do {
            $review_rating = rand(1, 10);
        } while ($review_rating == $review->rating);

        do {
            $review_header = mb_convert_encoding($this->faker->realText(rand(20, 67)), 'UTF-8');
        } while ($review_header == $review->header);

        do {
            $review_text = mb_convert_encoding($this->faker->realText(rand(100, 500)), 'UTF-8');
        } while ($review_text == $review->text);


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

