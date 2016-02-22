<?php

use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $book = new \App\Book();
        $book->name = 'Книга';
        $book->description = 'Описание книги';
        $book->cover = '/my_cover.png';
        $book->rars()->associate(\App\Rars::where('eternal_name', 'no_limits')->first());
       // $book->author()->associate(\App\User::where('email', 'pavel@alterbooks.ru')->first());
        $book->save();
    }
}
