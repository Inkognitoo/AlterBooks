<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Book extends Model
{
    /**
     * Получить автора книги
     */
    public function author()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Получить url обложки книги
     *
     * @return string
     */
    public function getCoverUrl()
    {
        if (!empty($this->cover)) {
            return Storage::disk('s3')->url('book_covers/' . $this->id . '/' . $this->cover);
        } else

        return '/img/default_book_cover.png';
    }

    /**
     * Получить url книги
     *
     * @return string
     */
    public function getUrl()
    {
        return route('book_show', ['id' => $this->id]);
    }
}
