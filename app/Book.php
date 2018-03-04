<?php

namespace App;

use App\Scopes\StatusScope;
use App\Traits\FindByIdOrSlugMethod;
use DB;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Storage;
use Exception;
use Cviebrock\EloquentSluggable\Sluggable;

/**
 * App\Book
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $description Описание книги с переводами строки заменёными на <br>
 * @property string|null $description_plain Описание книги как оно есть в бд
 * @property string|null $cover Название обложки книги
 * @property string $status Статус текущей книги (черновик/чистовик)
 * @property int $author_id
 * @property int $mongodb_book_id Идентификатор документа в MongoDB
 * @property int $page_count Количество страниц в книге
 * @property string $cover_path Путь до обложки книги в рамках Amazon S3
 * @property string $cover_url Ссылка на обложку книги
 * @property string $url Ссылка на книгу
 * @property string $status_css css класс соответствующий текущему статусу книги
 * @property float $rating Средняя оценка книги
 * @property string $slug Имя книги для формирования url
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \App\User $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users Коллекция пользователей добавивших к себе книгу
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Review[] $reviews
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Genre[] $genres
 * @property-write mixed $text
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Book onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereMongodbBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book wherePageCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Book withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Book withoutTrashed()
 * @method static bool|null forceDelete()
 * @method static bool|null restore()
 * @mixin Eloquent
 */
class Book extends Model
{
    use SoftDeletes, Sluggable, FindByIdOrSlugMethod;

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
        'genres', 'cover', 'text',
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
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
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
     * Все рецензии на текущую книгу
     */
    public function reviews()
    {
        return $this->hasMany('App\Review', 'book_id');
    }

    /**
     * Все жанры текущей книги
     */
    public function genres()
    {
        return $this->belongsToMany('App\Genre', 'books_genres')
            ->withTimestamps()
            ;
    }

    /**
     * Автор книги
     */
    public function author()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Произвести поиск книги с любым статусом по id[number] или slug, либо по условию: ['column', 'condition', 'value']
     *
     * @param $id mixed
     * @return Book|null
     */
    public static function findAny($id)
    {
        $query = self::withoutGlobalScope(StatusScope::class);
        if (is_array($id)) {
            $query->where($id);
        } else {
            $query->findByIdOrSlug($id);
        }

        return $query->first();
    }

    /**
     * Проверить, оставлял ли конкретный пользователь рецензию к книге
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
     * Проверить, есть ли у книги конкретный жанр
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
     * Проверить, закрыт ли доступ к книге
     *
     * @return bool
     */
    public function isClose(): bool
    {
        return $this->status === self::STATUS_CLOSE;
    }

    /**
     * Описание книги, с переводами строки заменёнными на <br>
     *
     * @param string $description
     * @return string
     */
    public function getDescriptionAttribute($description)
    {
        $pattern = '/(\r\n)/i';
        $replacement = '<br>';
        return preg_replace($pattern, $replacement, $description);
    }

    /**
     * Сохранить описание книги с экранированием опасных символов
     *
     * @param string $description
     */
    public function setDescriptionAttribute($description)
    {
        $this->attributes['description'] = htmlspecialchars($description, ENT_HTML5);
    }

    /**
     * Описание книги как есть
     *
     * @return string
     */
    public function getDescriptionPlainAttribute()
    {
        return $this->attributes['description'];
    }

    /**
     * Путь до обложки книги на Amazon S3
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
     * url обложки книги
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
     * url книги
     *
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return route('book.show', ['id' => $this->slug]);
    }

    /**
     * css класс для текущего статуса книги
     *
     * @return string
     */
    public function getStatusCssAttribute(): string
    {
        switch ($this->status) {
            case $this::STATUS_OPEN:
                $status_css = '';
                break;
            case $this::STATUS_CLOSE:
                $status_css = 'user-block-books__element_status_close';
                break;
            default:
                $status_css = 'user-block-books__element_status_close';
        }

        return $status_css;
    }

    /**
     * Медианный рейтинг книги
     *
     * @return float
     */
    public function getRatingAttribute(): float
    {
        return round($this->reviews->median('rating'), 1);
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
     * Сохранить книгу в mongoDB
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
     * Сохранить жанры для книги
     *
     * @param array $genres
     */
    public function setGenresAttribute($genres)
    {
        foreach ($genres as $genre_slug) {
            $genre = Genre::where(['slug' => $genre_slug])->first();
            if (!$this->hasGenre($genre)) {
                $this->genres()->save($genre);
            }
        }

        foreach ($this->genres as $genre) {
            if (!in_array($genre->slug, $genres)) {
                DB::table('books_genres')->where([
                    ['book_id', '=', $this->id],
                    ['genre_id', '=', $genre->id]
                ])->delete();
            }
        }
    }

    /**
     * Конкретная страница книги
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
}
