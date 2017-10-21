<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Storage;
use Mockery\Exception;

/**
 * App\Book
 *
 * @property-read \App\User $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users Коллекция пользователей добавивших к себе книгу
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $title
 * @property string|null $description
 * @property string|null $cover Название обложки книги
 * @property int $author_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $coverPath Путь до обложки книги в рамках Amazon S3
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
    //Путь по которому хранятся обложки для книг на Amazon S3
    const COVER_PATH = 'book_covers';

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
            return Storage::disk('s3')->url($this->coverPath);
        }

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

    /**
     * Установить обложку для книги
     *
     * @param UploadedFile $cover Обложка книги
     * @param bool $save Сохранять ли состояние модели после записи
     * @return bool
     */
    public function setCover(UploadedFile $cover, bool $save = false): bool
    {
        if (empty($this->id) && !$save) {
            throw new Exception('For setting cover, book must be present');
        }

        if ($save) {
            $this->save();
        }

        if (Storage::disk('s3')->exists($this->coverPath)) {
            Storage::disk('s3')->delete($this->coverPath);
        }

        $imageName = $this::COVER_PATH . '/' . $this->id;
        $storagePath = Storage::disk('s3')->put($imageName, $cover);
        $this->cover = basename($storagePath);

        if ($save) {
            $this->save();
        }

        return true;
    }

    /**
     * Получаем путь до обложки книги на Amazon S3
     *
     * @return string
     */
    public function getCoverPathAttribute()
    {
        if (empty($this->id)) {
            throw new Exception('For getting cover path, book must be present');
        }

        if (empty($this->cover)) {
            throw new Exception('For getting cover path, book\'s cover must be present');
        }

        return $this::COVER_PATH . '/' . $this->id . '/' . $this->cover;
    }

}
