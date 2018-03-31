<?php

namespace App\Models\Book;

interface BookFormat
{
    /**
     * Конвертировать текущий формат в формат AlterBooks-а
     *
     * @return mixed
     */
    public function convert();
}