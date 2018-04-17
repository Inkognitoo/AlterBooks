<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Genre
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Genre[] $genres
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Book[] $books
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre whereUpdatedAt($value)
 * @mixin \Eloquent
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
        return $this->belongsToMany(Book::class, 'books_genres')
            ->withTimestamps()
            ;
    }
}
