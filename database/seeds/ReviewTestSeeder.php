<?php

use Illuminate\Database\Seeder;

class ReviewTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\User::class, 10)->create()->each(function ($u) {
            $count = rand(1, 5);
            for ($i = 0; $i < $count; $i++) {
                $u->books()->save(factory(App\Models\Book::class)->make(['status' => \App\Models\Book::STATUS_OPEN]));
            }


            $genres = \App\Models\Genre::all();
            if (filled($genres)) {
                $u->books->each(function ($book) use ($genres) {
                    $book->genres()->attach(
                        $genres->random(rand(1, $genres->count()))->pluck('id')->toArray()
                    );
                });
            }

            $count = rand(1, 5);
            for ($i = 0; $i < $count; $i++) {
                $book = \App\Models\Book::inRandomOrder()->where('author_id', '!=', $u->id)->first();
                if (filled($book)) {
                    $u->reviews()->save(factory(App\Models\Review::class)->make([
                        'book_id' => $book->id,
                        'user_id' => $u->id
                    ]));
                }
            }

            $review = \App\Models\Review::inRandomOrder()->where('user_id', '!=', $u->id)->first();
            if (filled($review)) {
                $review->reviewEstimates()->save(factory(App\Models\ReviewEstimate::class)->make([
                    'user_id' => $u->id
                ]));
            }
        });
    }
}
