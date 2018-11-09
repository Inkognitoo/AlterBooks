<?php

namespace App\Models\Book;

use App\Models\Book;

interface BookFormat
{
    public const PAGE_SIZE = 10000; //символы

    /**
     * Конвертировать текущий формат в формат AlterBooks-а
     *
     * @return mixed
     */
    public function convert(): void;

    /**
     * Вернуть экземпляр книги относящийся к текущему книжному формату
     *
     * @return Book
     */
    public function getBook(): Book;
}