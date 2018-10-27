<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $book_id
 * @property int $chapter_id
 * @property int $number
 * @property string $text
 * @property string $created_at
 * @property string $updated_at
 * @property Book $book
 * @property Chapter $chapter
 */
class Page extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function book()
    {
        return $this->belongsTo('App\Book');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function chapter()
    {
        return $this->belongsTo('App\Chapter');
    }
}
