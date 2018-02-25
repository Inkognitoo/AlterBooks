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
 * @property string|null $avatar Название аватарки пользователя
 * @property string $avatar_path Путь до аватара пользователя в рамках Amazon S3
 * @property string $avatar_url Ссылка на аватар пользователя
 * @property string $url Ссылка на пользователя
 * @property string $full_name ФИО пользователя
 * @property string $timezone таймзона пользователя
 * @property string $about Информация "О себе" с переводами строки заменёными на <br>
 * @property string $about_plain Информация "О себе" как она есть в бд
 * @property string $api_token Токен пользователя для api запросов
 * @property float $rating Средняя оценка книги
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Book[] $books Список книг созданных пользователем
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Book[] $libraryBooks Список книг добавленных пользователем в свою библиотеку
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ReviewEstimate[] $reviewEstimates Оценки к рецензиями оставленные пользователем
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Review[] $reviews
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTimezone($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable;

    //Возможные гендеры пользователя
    const GENDER_MALE = 'm';

    const GENDER_FEMALE = 'f';

    const GENDER_NOT_INDICATED = 'n';

    //Путь по которому хранятся аватары для пользователей на Amazon S3
    const AVATAR_PATH = 'avatars';

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
     * Книги, автором которых является текущий пользователь
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
     * Рецензии текущего пользователя
     */
    public function reviews()
    {
        return $this->hasMany('App\Review', 'user_id');
    }

    /**
     * Оценки на рецензии оставленные текущим пользователем
     */
    public function reviewEstimates()
    {
        return $this->hasMany('App\ReviewEstimate', 'user_id');
    }

    /**
     * Экземпляр книги в библиотеке пользователя, если таковой имеется
     *
     * @param Book $book
     * @return Book|null
     */
    public function getLibraryBook(Book $book)
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
     * Информация о себе, с переводами строки заменёнными на <br>
     *
     * @param string $about
     * @return string
     */
    public function getAboutAttribute($about)
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
    public function setAboutAttribute($about)
    {
        $this->attributes['about'] = htmlspecialchars($about, ENT_HTML5);
    }

    /**
     * Информация о себе как есть
     *
     * @return string
     */
    public function getAboutPlainAttribute()
    {
        return $this->attributes['about'];
    }

    /**
     * Путь до аватары пользователя на Amazon S3
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
     * url аватары пользователя
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
        return route('user.show', ['id' => $this->id]);
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
    public function getBirthdayDatePlainAttribute()
    {
        return $this->attributes['birthday_date'];
    }

    /**
     * Установить аватар для пользователя
     *
     * @param UploadedFile $avatar Аватар пользователя
     * @return void
     * @throws Exception
     */
    public function setAvatarAttribute(UploadedFile $avatar)
    {
        if (blank($this->id)) {
            throw new Exception('For setting avatar path, user must be present');
        }

        if (filled($this->avatar) && Storage::disk('s3')->exists($this->avatar_path)) {
            Storage::disk('s3')->delete($this->avatar_path);
        }

        $image_name = $this::AVATAR_PATH . '/' . $this->id;
        $storage_path = Storage::disk('s3')->put($image_name, $avatar);
        $this->attributes['avatar'] = basename($storage_path);
    }

    /**
     * Записать email для пользователя в нижнем регистре
     *
     * @param string $email
     */
    public function setEmailAttribute(string $email)
    {
        $this->attributes['email'] = mb_strtolower($email);
    }

    /**
     * Записать хэш нового пароля пользователя
     *
     * @param string $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function getNicknameAttribute()
    {
        return mb_strtolower($this->attributes['nickname']);
    }
}
