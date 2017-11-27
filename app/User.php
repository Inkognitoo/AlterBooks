<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Exception;
use Storage;

/**
 * App\User
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Book[] $books Список книг созданных пользователем
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Book[] $libraryBooks Список книг добавленных пользователем в свою библиотеку
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $surname
 * @property string|null $patronymic
 * @property string $gender
 * @property string $nickname
 * @property Carbon|null $birthday_date
 * @property string|null $birthday_date_plain
 * @property string|null $avatar Название аватарки пользователя
 * @property string $avatar_path Путь до аватара пользователя в рамках Amazon S3
 * @property string $avatar_url Ссылка на аватар пользователя
 * @property string $url Ссылка на пользователя
 * @property string $full_name ФИО пользователя
 * @property string $timezone таймзона пользователя
 * @property string $about Информация "О себе" с переводами строки заменёными на <br>
 * @property string $about_plain Информация "О себе" как она есть в бд
 * @property float $rating Средняя оценка книги
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBirthdayDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePatronymic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Review[] $reviews
 */
class User extends Authenticatable
{
    //Возможные гендеры пользователя
    const GENDER_MALE = 'm';
    const GENDER_FEMALE = 'f';
    const GENDER_NOT_INDICATED = 'n';

    //Путь по которому хранятся аватары для пользователей на Amazon S3
    const AVATAR_PATH = 'avatars';

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nickname', 'email', 'surname',
        'patronymic', 'birthday_date', 'gender',
        'about', 'timezone',
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
     * Получить все книги, автором которых является пользователь
     */
    public function books()
    {
        return $this->hasMany('App\Book', 'author_id');
    }

    /**
     * Книги в библиотеке пользователя
     */
    public function libraryBooks()
    {
        return $this->belongsToMany('App\Book', 'users_library')
            ->withTimestamps()
        ;
    }

    /**
     * Получить все рецензии текущего пользователя
     */
    public function reviews()
    {
        return $this->hasMany('App\Review', 'user_id');
    }

    /**
     * Проверить есть ли у пользователя в библиотеке данная книга
     *
     * @param $book Book
     * @return boolean
     */
    public function hasBookAtLibrary(Book $book): bool
    {
        return ($this->libraryBooks()->where(['book_id' => $book->id])->get()->count() !== 0);
    }

    /**
     * Получить экземпляр книги в библиотеке пользователя, если таковой имеется
     *
     * @param Book $book
     * @return Book|null
     */
    public function getLibraryBook(Book $book)
    {
        return $this->libraryBooks()->where(['book_id' => $book->id])->first();
    }

    /**
     * Установить аватар для пользователя
     *
     * @param UploadedFile $avatar Аватар пользователя
     * @param bool $save Сохранять ли состояние модели после записи
     * @return void
     * @throws Exception
     */
    public function setAvatar(UploadedFile $avatar, bool $save = false)
    {
        if (blank($this->id) && !$save) {
            throw new Exception('For setting avatar path, user must be present');
        }

        if ($save) {
            $this->save();
        }

        if (Storage::disk('s3')->exists($this->avatar_path)) {
            Storage::disk('s3')->delete($this->avatar_path);
        }

        $image_name = $this::AVATAR_PATH . '/' . $this->id;
        $storage_path = Storage::disk('s3')->put($image_name, $avatar);
        $this->avatar = basename($storage_path);

        if ($save) {
            $this->save();
        }
    }

    /**
     * Получить url аватары пользователя
     *
     * @return string
     */
    public function getAvatarUrlAttribute(): string
    {
        if (filled($this->avatar)) {
            return Storage::disk('s3')->url($this->avatar_path);
        }

        switch ($this->gender) {
            case $this::GENDER_MALE:
                return '/img/avatar_man.png';
                break;
            case $this::GENDER_FEMALE:
                return '/img/avatar_woman.png';
                break;
            default:
                return '/img/avatar_default.png';
                break;
        }
    }

    /**
     * Получить url пользователя
     *
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return route('user.show', ['id' => $this->id]);
    }

    /**
     * Получаем путь до аватарки пользователя на Amazon S3
     *
     * @return string
     * @throws Exception
     */
    public function getAvatarPathAttribute(): string
    {
        if (blank($this->id)) {
            throw new Exception('For getting avatar path, user must be present');
        }

        if (blank($this->avatar)) {
            throw new Exception('For getting avatar path, user\'s avatar must be present');
        }

        return $this::AVATAR_PATH . '/' . $this->id . '/' . $this->avatar;
    }

    /**
     * Вернуть ФИО пользователя, либо значение по умолчанию
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
     * Проверить, оставлял ли пользователь рецензию к книге
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
     * Проверить, является ли текущий пользователь автором указанной книги
     *
     * @param Book $book
     * @return bool
     */
    public function isAuthor(Book $book): bool
    {
        return $this->id === $book->author_id;
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
     * Экранировать опасные символы в записываемой информации "О себе"
     *
     * @param string $value
     */
    public function setAboutAttribute($value)
    {
        $this->attributes['about'] = htmlspecialchars($value, ENT_HTML5);
    }

    /**
     * Вывести информацию "О себе", заменяя переводы строки на <br>
     *
     * @param string $value
     * @return string
     */
    public function getAboutAttribute($value)
    {
        $pattern = '/(\r\n)/i';
        $replacement = '<br>';
        return preg_replace($pattern, $replacement, $value);
    }

    /**
     * Вывести информацию "О себе" как есть в бд
     *
     * @return string
     */
    public function getAboutPlainAttribute()
    {
        return $this->attributes['about'];
    }

    /**
     * Получаем медианный рейтинг пользователя
     *
     * @return float
     */
    public function getRatingAttribute(): float
    {
        return round($this->books->median('rating'), 1);
    }

    /**
     * Вывести дату рождения пользователя, если указана
     *
     * @param string $value
     * @return \Carbon\Carbon|null
     */
    public function getBirthdayDateAttribute($value)
    {
        return is_null($value) ? null : new Carbon($value);
    }

    /**
     * Вывести дату рождения пользователя как есть в бд
     *
     * @return string|null
     */
    public function getBirthdayDatePlainAttribute()
    {
        return $this->attributes['birthday_date'];
    }
}
