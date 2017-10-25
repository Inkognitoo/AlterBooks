<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use MongoDB;
use MongoDB\BSON\ObjectID;
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
 * @property int $mongodb_book_id Идентификатор документа в MongoDB
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $cover_path Путь до обложки книги в рамках Amazon S3
 * @property string $cover_url Ссылка на обложку книги
 * @property string $url Ссылка на книгу
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
     * Установить обложку для книги
     *
     * @param UploadedFile $cover Обложка книги
     * @param bool $save Сохранять ли состояние модели после записи
     * @return bool
     */
    public function setCover(UploadedFile $cover, bool $save = false): bool
    {
        if (blank($this->id) && !$save) {
            throw new Exception('For setting cover, book must be present');
        }

        if ($save) {
            $this->save();
        }

        if (Storage::disk('s3')->exists($this->cover_path)) {
            Storage::disk('s3')->delete($this->cover_path);
        }

        $image_name = $this::COVER_PATH . '/' . $this->id;
        $storage_path = Storage::disk('s3')->put($image_name, $cover);
        $this->cover = basename($storage_path);

        if ($save) {
            $this->save();
        }

        return true;
    }

    /**
     * Получить url обложки книги
     *
     * @return string
     */
    public function getCoverUrlAttribute(): string
    {
        if (filled($this->cover)) {
            return Storage::disk('s3')->url($this->cover_path);
        }

        return '/img/default_book_cover.png';
    }

    /**
     * Получить url книги
     *
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return route('book.show', ['id' => $this->id]);
    }

    /**
     * Получаем путь до обложки книги на Amazon S3
     *
     * @return string
     */
    public function getCoverPathAttribute(): string
    {
        if (blank($this->id)) {
            throw new Exception('For getting cover path, book must be present');
        }

        if (blank($this->cover)) {
            throw new Exception('For getting cover path, book\'s cover must be present');
        }

        return $this::COVER_PATH . '/' . $this->id . '/' . $this->cover;
    }

    /**
     * Сохраняем книгу в mongoDB
     *
     * @param UploadedFile $text Текст книги
     * @param bool $save Сохранять ли состояние модели после записи
     * @return bool
     */
    public function setText(UploadedFile $text, bool $save = false): bool
    {
        if (blank($this->id) && !$save) {
            throw new Exception('For setting text, book must be present');
        }

        if ($save) {
            $this->save();
        }

        $mongodb_book = new MongoBook($this);
        $this->mongodb_book_id = $mongodb_book->setText($text);

        if ($save) {
            $this->save();
        }

        return true;
    }

    /**
     * Получить конкретную страницу книги
     *
     * @param int $page_number Номер запрашиваемой страницы
     * @return null|string
     */
    public function getPage(int $page_number)
    {
        $mongodb_book = new MongoBook($this);

        return $mongodb_book->getPage($page_number);
    }
}
