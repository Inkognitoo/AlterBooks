<?php

use Illuminate\Database\Seeder;

class ReviewEstimateTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 10)->create()->each(function ($u) {
            $count = rand(1, 5);
            for ($i = 0; $i < $count; $i++) {
                $u->books()->save(factory(App\Book::class)->make(['status' => \App\Book::STATUS_OPEN]));
            }

            $count = rand(1, 5);
            for ($i = 0; $i < $count; $i++) {
                $book = \App\Book::inRandomOrder()->where('author_id', '!=', $u->id)->first();
                if (filled($book)) {
                    $u->reviews()->save(factory(App\Review::class)->make([
                        'book_id' => $book->id,
                        'user_id' => $u->id
                    ]));
                }
            }

            $review = \App\Review::inRandomOrder()->where('user_id', '!=', $u->id)->first();
            if (filled($review)) {
                $review->reviewEstimates()->save(factory(App\ReviewEstimate::class)->make([
                    'user_id' => $u->id
                ]));
            }
        });
    }
}
