<?php

use Illuminate\Database\Seeder;
use App\BookStatus;

class BookStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $book_statuses_data = [
            'internal_name' => 'draft',
            'ru_name' => 'Черновик',
            'en_name' => 'Draft'
        ];
        $book_statuses = new BookStatus($book_statuses_data);
        $book_statuses->save();

        $book_statuses_data = [
            'internal_name' => 'published',
            'ru_name' => 'Опубликовано',
            'en_name' => 'Published'
        ];
        $book_statuses = new BookStatus($book_statuses_data);
        $book_statuses->save();

        $book_statuses_data = [
            'internal_name' => 'blocked',
            'ru_name' => 'Заблокированный',
            'en_name' => 'Blocked'
        ];
        $book_statuses = new BookStatus($book_statuses_data);
        $book_statuses->save();

        $book_statuses_data = [
            'internal_name' => 'banned',
            'ru_name' => 'Запрещённый',
            'en_name' => 'Banned'
        ];
        $book_statuses = new BookStatus($book_statuses_data);
        $book_statuses->save();
    }
}
