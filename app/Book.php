<?php

namespace App;

use App\Scopes\StatusScope;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Storage;
use Exception;

/**
 * App\Book
 *
 * @property-read \App\User $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users Коллекция пользователей добавивших к себе книгу
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $title
 * @property string|null $description Описание книги с переводами строки заменёными на <br>
 * @property string|null $description_plain Описание книги как оно есть в бд
 * @property string|null $cover Название обложки книги
 * @property string $status Статус текущей книги (черновик/чистовик)
 * @property int $author_id
 * @property int $mongodb_book_id Идентификатор документа в MongoDB
 * @property int $page_count Количество страниц в книге
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property string $cover_path Путь до обложки книги в рамках Amazon S3
 * @property string $cover_url Ссылка на обложку книги
 * @property string $status_css css класс соответствующий текущему статусу книги
 * @property string $url Ссылка на книгу
 * @property float $rating Средняя оценка книги
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Review[] $reviews
 * @method static \Illuminate\Database\Query\Builder|\App\Book onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereMongodbBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book wherePageCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Book withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Book withoutTrashed()
 * @method static bool|null forceDelete()
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Genre[] $genres
 */
class Book extends Model
{
    use SoftDeletes;

    //Путь по которому хранятся обложки для книг на Amazon S3
    const COVER_PATH = 'book_covers';

    //Возможные статусы книги
    const STATUS_OPEN = 'open_by_author';

    const STATUS_CLOSE = 'close_by_author';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'status',
        'genres',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new StatusScope);
    }

    /**
     * Находим книгу с любым статусом
     *
     * @param $id int
     * @return Book|null
     */
    public static function findAny(int $id)
    {
        return self::withoutGlobalScope(StatusScope::class)
            ->where('id', $id)
            ->first()
        ;
    }

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
     * Получить все рецензии на текущую книгу
     */
    public function reviews()
    {
        return $this->hasMany('App\Review', 'book_id');
    }

    /**
     * Жанры книги
     */
    public function genres()
    {
        return $this->belongsToMany('App\Genre', 'books_genres')
            ->withTimestamps()
            ;
    }

    /**
     * Установить обложку для книги
     *
     * @param UploadedFile $cover Обложка книги
     * @return void
     * @throws Exception
     */
    public function setCoverAttribute(UploadedFile $cover)
    {
        if (blank($this->id)) {
            throw new Exception('For setting cover, book must be present');
        }

        if (filled($this->cover) && Storage::disk('s3')->exists($this->cover_path)) {
            Storage::disk('s3')->delete($this->cover_path);
        }

        $image_name = $this::COVER_PATH . '/' . $this->id;
        $storage_path = Storage::disk('s3')->put($image_name, $cover);
        $this->attributes['cover'] = basename($storage_path);
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
     * @throws Exception
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
     * Получаем css класс для текущего статуса книги
     *
     * @return string
     */
    public function getStatusCssAttribute(): string
    {
        switch ($this->status) {
            case $this::STATUS_OPEN:

                return '';
            case $this::STATUS_CLOSE:

                return 'user-block-books__element_status_close';
            default:

                return 'user-block-books__element_status_close';
        }
    }

    /**
     * Получаем медианный рейтинг книги
     *
     * @return float
     */
    public function getRatingAttribute(): float
    {
        return round($this->reviews->median('rating'), 1);
    }

    /**
     * Сохраняем книгу в mongoDB
     *
     * @param UploadedFile $text Текст книги
     * @return void
     * @throws Exception
     */
    public function setTextAttribute(UploadedFile $text)
    {
        if (blank($this->id)) {
            throw new Exception('For setting text, book must be present');
        }

        $mongodb_book = new MongoBook($this);
        $mongodb_book->setText($text);
    }

    /**
     * Получить конкретную страницу книги
     *
     * @param int $page_number Номер запрашиваемой страницы
     * @param bool $format Нужно ли форматировать возвращаемый текст в html
     * @return null|string
     * @throws Exception
     */
    public function getPage(int $page_number, bool $format = true)
    {
        $mongodb_book = new MongoBook($this);

        return $mongodb_book->getPage($page_number, $format);
    }

    /**
     * Обновить конкретную страницу книги
     *
     * @param int $page_number Номер редактируемой страницы
     * @param string $text Новый текст страницы
     * @return void
     * @throws Exception
     */
    public function editPage(int $page_number, string $text)
    {
        $mongodb_book = new MongoBook($this);

        $mongodb_book->editPage($page_number, $text);
    }

    /**
     * Проверить, оставлял ли пользователь рецензию к книге
     *
     * @param User $user
     * @return bool
     */
    public function hasReview(User $user): bool
    {
        return filled($this->reviews()
            ->where(['user_id' => $user->id])
            ->first()
        );
    }

    /**
     * Проверить, закрыт ли доступ к книге
     *
     * @return bool
     */
    public function isClose(): bool
    {
        return $this->status === self::STATUS_CLOSE;
    }

    /**
     * Проверить, есть ли у книги этот жанр
     *
     * @param Genre $genre
     * @return bool
     */
    public function hasGenre(Genre $genre): bool
    {
        return filled($this->genres()
            ->where(['genre_id' => $genre->id])
            ->first()
        );
    }

    /**
     * Сохранить описание книги с экранированием опасных символов
     *
     * @param string $value
     */
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = htmlspecialchars($value, ENT_HTML5);
    }

    /**
     * Вывести описание книги, заменяя переводы строки на <br>
     *
     * @param string $value
     * @return string
     */
    public function getDescriptionAttribute($value)
    {
        $pattern = '/(\r\n)/i';
        $replacement = '<br>';
        return preg_replace($pattern, $replacement, $value);
    }

    /**
     * Вывести описание книги как есть
     *
     * @return string
     */
    public function getDescriptionPlainAttribute()
    {
        return $this->attributes['description'];
    }

    /**
     * Сохранить жанры для книги
     *
     * @param array $values
     */
    public function setGenresAttribute($values)
    {
        foreach ($values as $value) {
            $genre = Genre::where(['slug' => $value])->first();
            if (!$this->hasGenre($genre)) {
                $this->genres()->save($genre);
            }
        }
        foreach ($this->genres as $genre) {
            if (!in_array($genre->slug, $values)) {
                DB::table('books_genres')->where([
                    ['book_id', '=', $this->id],
                    ['genre_id', '=', $genre->id]
                ])->delete();
            }
        }
    }
}
