<?php

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewEditTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var User $user */

        factory(App\Models\User::class, 10)->create()->each(function ($user) {
            $count = rand(1, 5);
            for ($i = 0; $i < $count; $i++) {
                $user->books()->save(factory(Book::class)->make(['status' => Book::STATUS_OPEN]));
            }


            $genres = \App\Models\Genre::all();
            if (filled($genres)) {
                $user->books->each(function ($book) use ($genres) {
                    $book->genres()->attach(
                        $genres->random(rand(1, $genres->count()))->pluck('id')->toArray()
                    );
                });
            }

            $count = rand(1, 5);
            for ($i = 0; $i < $count; $i++) {
                $book = Book::inRandomOrder()->where('author_id', '!=', $user->id)->first();
                if (filled($book)) {
                    $user->reviews()->save(factory(Review::class)->make([
                        'book_id' => $book->id
                    ]));
                }
            }
        });
    }
}
