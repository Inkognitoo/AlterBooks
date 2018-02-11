<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Genre
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Genre whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Genre whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Genre whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Genre whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Genre whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Book[] $books
 */
class Genre extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug',
    ];

    /**
     * Книги этого жанра
     */
    public function books()
    {
        return $this->belongsToMany('App\Book', 'books_genres')
            ->withTimestamps()
            ;
    }
}
