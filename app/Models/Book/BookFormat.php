<?php

namespace App\Models\Book;

use App\Models\Book;

interface BookFormat
{
    /**
     * Конвертировать текущий формат в формат AlterBooks-а
     *
     * @return mixed
     */
    public function convert();

    /**
     * Вернуть экземпляр книги относящийся к текущему книжному формату
     *
     * @return Book
     */
    public function getBook(): Book;
}