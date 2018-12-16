<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use App\Traits\FindByIdOrSlugMethod;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Exception;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;
use RuntimeException;
use Storage;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string|null $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property string|null $surname
 * @property string|null $patronymic
 * @property string $gender
 * @property string $nickname
 * @property Carbon|null $birthday_date
 * @property string|null $birthday_date_plain
 * @property Carbon|null $last_activity_at Время последнего действия пользователя
 * @property string|null $avatar Название аватарки пользователя
 * @property string $avatar_path Путь до аватара пользователя в рамках файлового хранилища
 * @property string $avatar_url Ссылка на аватар пользователя
 * @property string $url Ссылка на пользователя
 * @property string $full_name ФИО пользователя
 * @property string $timezone таймзона пользователя
 * @property string|null $about Информация "О себе" с переводами строки заменёными на <br>
 * @property string|null $about_plain Информация "О себе" как она есть в бд
 * @property string $api_token Токен пользователя для api запросов
 * @property float $rating Средняя оценка книги
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Book[] $books Список книг созданных пользователем
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Book[] $libraryBooks Список книг добавленных пользователем в свою библиотеку
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ReviewEstimate[] $reviewEstimates Оценки к рецензиями оставленные пользователем
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Review[] $reviews
 * @property-read string $canonical_url Каноничный (основной, постоянный) url пользователя
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereBirthdayDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePatronymic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereTimezone($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User findByIdOrSlug($id, $slug_name = null)
 * @property bool $is_admin
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereIsAdmin($value)
 */
class User extends Authenticatable
{
    use Notifiable, SoftDeletes, FindByIdOrSlugMethod;

    //Возможные гендеры пользователя
    public const GENDER_MALE = 'm';

    public const GENDER_FEMALE = 'f';

    public const GENDER_NOT_INDICATED = 'n';

    //Подпапка в которой хранятся аватары пользователей
    public const AVATAR_PATH = 'avatars';

    //Поле для поиска по slug через трейт FindByIdOrSlugMethod
    public const SLUG_NAME = 'nickname';

    //Количество минут бездействия пользователя, поддерживающее его статус Online
    public const ONLINE_ENDED = 12;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nickname', 'email', 'name',
        'surname', 'patronymic', 'birthday_date',
        'gender', 'about', 'timezone',
        'password', 'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Книги, автором которых является текущий пользователь
     *
     * @return HasMany
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'author_id');
    }

    /**
     * Книги в библиотеке пользователя
     *
     * @return BelongsToMany
     */
    public function libraryBooks(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'users_library')
            ->withTimestamps()
        ;
    }

    /**
     * Рецензии текущего пользователя
     *
     * @return HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    /**
     * Оценки на рецензии оставленные текущим пользователем
     *
     * @return HasMany
     */
    public function reviewEstimates(): HasMany
    {
        return $this->hasMany(ReviewEstimate::class, 'user_id');
    }

    /**
     * Экземпляр книги в библиотеке пользователя, если таковой имеется
     *
     * @param Book $book
     * @return Book|null
     */
    public function getLibraryBook(Book $book): ?Book
    {
        return $this->libraryBooks()->where(['book_id' => $book->id])->first();
    }

    /**
     * Проверить, есть ли у пользователя в библиотеке конкретная книга
     *
     * @param $book Book
     * @return boolean
     */
    public function hasBookAtLibrary(Book $book): bool
    {
        return ($this->libraryBooks()->where(['book_id' => $book->id])->get()->count() !== 0);
    }

    /**
     * Проверить, оставлял ли пользователь рецензию к конкретной книге
     *
     * @param Book $book
     * @return bool
     */
    public function hasBookReview(Book $book): bool
    {
        return filled($this->reviews()
            ->where(['book_id' => $book->id])
            ->first()
        );
    }

    /**
     * Вернуть рецензию пользователя на конкретную книгу
     *
     * @param Book $book
     * @return Review $review
     */
    public function getBookReview(Book $book): ?Review
    {
        return $this->reviews()
            ->where(['book_id' => $book->id])
            ->first()
        ;
    }

    /**
     * Проверить, оставлял ли пользователь конкретную рецензию
     *
     * @param Review $review
     * @return bool
     */
    public function hasReview(Review $review): bool
    {
        return filled($this->reviews()
            ->find($review->id)
        );
    }

    /**
     * Проверить, является ли текущий пользователь автором конкретной книги
     *
     * @param Book $book
     * @return bool
     */
    public function isAuthor(Book $book): bool
    {
        return $this->id === $book->author_id;
    }

    /**
     * Проверить, является ли текущий пользователь online
     *
     * @return bool
     */
    public function isOnline(): bool
    {
        return Carbon::parse($this->last_activity_at)->addMinutes(self::ONLINE_ENDED) > Carbon::now();
    }

    /**
     * Информация о себе, с переводами строки заменёнными на <br>
     *
     * @param string $about
     * @return string
     */
    public function getAboutAttribute($about): string
    {
        $pattern = '/(\r\n)/i';
        $replacement = '<br>';
        return preg_replace($pattern, $replacement, $about);
    }

    /**
     * Сохранить информацию о себе с экранированием опасных символов
     *
     * @param string $about
     */
    public function setAboutAttribute($about): void
    {
        $this->attributes['about'] = htmlspecialchars($about, ENT_HTML5);
    }

    /**
     * Информация о себе как есть
     *
     * @return string
     */
    public function getAboutPlainAttribute(): string
    {
        return (string)$this->attributes['about'];
    }

    /**
     * Путь до аватары пользователя в файловом хранилище
     *
     * @return string
     * @throws RuntimeException
     */
    public function getAvatarPathAttribute(): string
    {
        if (blank($this->id)) {
            throw new RuntimeException('For getting avatar path, user must be present');
        }

        if (blank($this->avatar)) {
            throw new RuntimeException('For getting avatar path, user\'s avatar must be present');
        }

        return $this::AVATAR_PATH . '/' . $this->id . '/' . $this->avatar;
    }

    /**
     * url аватары пользователя
     *
     * @return string
     */
    public function getAvatarUrlAttribute(): string
    {
        if (filled($this->avatar)) {
            return Storage::url($this->avatar_path);
        }

        switch ($this->gender) {
            case $this::GENDER_MALE:
                $avatar_url = '/img/avatar_man.png';
                break;
            case $this::GENDER_FEMALE:
                $avatar_url = '/img/avatar_woman.png';
                break;
            default:
                $avatar_url = '/img/avatar_default.png';
                break;
        }

        return $avatar_url;
    }

    /**
     * url пользователя
     *
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return route('user.show', ['id' => $this->nickname]);
    }

    /**
     * ФИО пользователя, либо значение по умолчанию (ник)
     *
     * @return string
     * */
    public function getFullNameAttribute(): string
    {
        if (blank($this->name)) {
            return $this->nickname;
        }
        return $this->surname . ' ' . $this->name . ' ' . $this->patronymic;
    }

    /**
     * Медианный рейтинг пользователя
     *
     * @return float
     */
    public function getRatingAttribute(): float
    {
        return round($this->books->median('rating'), 1);
    }

    /**
     * Дата рождения пользователя, если указана
     *
     * @param string $birthday_date
     * @return \Carbon\Carbon|null
     */
    public function getBirthdayDateAttribute($birthday_date)
    {
        return is_null($birthday_date) ? null : new Carbon($birthday_date);
    }

    /**
     * Дата рождения пользователя как есть
     *
     * @return string|null
     */
    public function getBirthdayDatePlainAttribute(): ?string
    {
        return $this->attributes['birthday_date'];
    }

    /**
     * Каноничный (основной, постоянный) url пользователя
     *
     * @return string
     */
    public function getCanonicalUrlAttribute(): string
    {
        return route('user.show', ['id' => 'id' . $this->id]);
    }

    /**
     * Аватара пользователя (как есть в бд)
     *
     * Эти костыли нужны из-за магии laravel, не позволяющей спокойно юзать
     * свойство avatar при наличии одноимённой функции в свежесозданном
     * экземпляре класса
     *
     * @return string
     */
    public function getAvatarAttribute(): string
    {
        return array_key_exists('avatar', $this->attributes) ? (string)$this->attributes['avatar'] : '';
    }

    /**
     * Установить аватар для пользователя
     *
     * @param UploadedFile $avatar Аватар пользователя
     * @return void
     * @throws RuntimeException
     */
    public function setAvatarAttribute(UploadedFile $avatar): void
    {
        if (blank($this->id)) {
            throw new RuntimeException('For setting avatar path, user must be present');
        }

        if (filled($this->avatar) && Storage::exists($this->avatar_path)) {
            Storage::delete($this->avatar_path);
        }

        $image_name = $this::AVATAR_PATH . '/' . $this->id;
        $storage_path = Storage::put($image_name, $avatar);
        $this->attributes['avatar'] = basename($storage_path);
    }

    /**
     * Записать email для пользователя в нижнем регистре
     *
     * @param string $email
     */
    public function setEmailAttribute(string $email): void
    {
        $this->attributes['email'] = mb_strtolower($email);
    }

    /**
     * Записать хэш нового пароля пользователя
     *
     * @param string $password
     */
    public function setPasswordAttribute($password): void
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * Аватар пользователя произвольного размера
     *
     * @param int $height
     * @param int $width
     * @return string
     */
    public function avatar(int $width = null, int $height = null): string
    {
        if ((is_null($width) && is_null($height)) || blank($this->avatar)) {
            return $this->avatar_url;
        }

        $fit_avatar_path = str_before($this->avatar_path, $this->avatar) . 'thumbs/' . $width . 'x' . $height . '/' . $this->avatar;
        if (Storage::exists($fit_avatar_path)) {
            return Storage::url($fit_avatar_path);
        }

        $avatar = Image::make(Storage::path($this->avatar_path))
            ->fit($width, $height, function ($constraint) {
                /** @var Constraint $constraint  */
                $constraint->aspectRatio();
            });
        Storage::put($fit_avatar_path, (string)$avatar->encode());
        return Storage::url($fit_avatar_path);
    }
}
