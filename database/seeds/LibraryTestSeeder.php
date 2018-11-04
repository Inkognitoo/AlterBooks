<?php

use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Seeder;

class LibraryTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 10)->create()->each(function ($user) {
            $count = random_int(1, 5);
            for ($i = 0; $i < $count; $i++) {
                /** @var User $user */
                $user->books()->save(factory(Book::class)->make(['status' => Book::STATUS_OPEN]));
            }
        });
    }
}
