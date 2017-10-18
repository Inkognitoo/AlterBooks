<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;

/**
 * App\Book
 *
 * @property-read \App\User $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $title
 * @property string|null $description
 * @property string|null $cover
 * @property int $author_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereUpdatedAt($value)
 */
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
     * Пользователи, добавившие книгу к себе в библиотеку
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'users_library')
            ->withTimestamps()
        ;
    }

    /**
     * Получить url обложки книги
     *
     * @return string
     */
    public function getCoverUrl(): string
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
    public function getUrl(): string
    {
        return route('book_show', ['id' => $this->id]);
    }
}
