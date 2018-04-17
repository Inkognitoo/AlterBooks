<?php

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
        factory(App\Models\User::class, 10)->create()->each(function ($u) {
            $count = rand(1, 5);
            for ($i = 0; $i < $count; $i++) {
                $u->books()->save(factory(App\Models\Book::class)->make(['status' => \App\Models\Book::STATUS_OPEN]));
            }
        });
    }
}
