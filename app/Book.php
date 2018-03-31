<?php

namespace App;

use App\Jobs\ProcessBook;
use App\Models\Book\Txt;
use App\Scopes\ProcessingScope;
use App\Scopes\StatusScope;
use App\Traits\FindByIdOrSlugMethod;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Storage;
use Exception;
use Cviebrock\EloquentSluggable\Sluggable;
use File;

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
 * @property-read \Illuminate\Filesystem\FilesystemAdapter $storage
 * @property-read string $canonical_url Каноничный (основной, постоянный) url книги
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book findByIdOrSlug($id, $slug_name = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book findSimilarSlugs($attribute, $config, $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereSlug($value)
 * @property bool $is_processing
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Book whereIsProcessing($value)
 */
class Book extends Model
{
    use SoftDeletes, Sluggable, FindByIdOrSlugMethod;

    //Подпапка в которой хранятся обложки для книг
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

    // Закэшированный компонент Storage
    protected $_storage = null;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new StatusScope);
        static::addGlobalScope(new ProcessingScope);
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
        return $this->belongsToMany(User::class, 'users_library')
            ->withTimestamps()
        ;
    }

    /**
     * Все рецензии на текущую книгу
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'book_id');
    }

    /**
     * Все жанры текущей книги
     */
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'books_genres')
            ->withTimestamps()
            ;
    }

    /**
     * Автор книги
     */
    public function author()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Произвести поиск книги с любым статусом по id[number] или slug, либо по условию: ['column', 'condition', 'value']
     *
     * @param $id mixed
     * @return Book|null
     */
    public static function findAny($id)
    {
        $query = self::withoutGlobalScopes([StatusScope::class, ProcessingScope::class]);
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
     * Проверить, можно ли читать книгу
     *
     * @return bool
     */
    public function isReadable(): bool
    {
        return $this->is_processing === false && !empty($this->mongodb_book_id);
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
            return $this->storage->url($this->cover_path);
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
     * Текущий драйвер файлового хранилища
     *
     * @return \Illuminate\Filesystem\FilesystemAdapter|null
     */
    public function getStorageAttribute()
    {
        if (empty($this->_storage)) {
            $this->_storage = Storage::disk('s3');
        }

        return $this->_storage;
    }

    /**
     * Каноничный (основной, постоянный) url книги
     *
     * @return string
     */
    public function getCanonicalUrlAttribute()
    {
        return route('book.show', ['id' => 'id' . $this->id]);
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

        if (filled($this->cover) && $this->storage->exists($this->cover_path)) {
            $this->storage->delete($this->cover_path);
        }

        $image_name = $this::COVER_PATH . '/' . $this->id;
        $storage_path = $this->storage->put($image_name, $cover);
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

        switch (File::mimeType($text->path())) {
            case 'text/plain':
                $path = $text->store($text->path());
                $converter = new Txt($this, storage_path('app/' . $path));
                break;
            default:
                throw new Exception('Book\'s format is not allow');

        }

        $this->is_processing = true;
        ProcessBook::dispatch($converter);
    }

    /**
     * Сохранить жанры для книги
     *
     * @param array $genres
     */
    public function setGenresAttribute($genres)
    {
        $genres = Genre::whereIn('slug', $genres)->get();
        $this->genres()->sync($genres);
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
