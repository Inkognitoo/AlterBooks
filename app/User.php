<?php

namespace App;

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
 * @property string|null $birthday_date
 * @property string|null $avatar Название аватарки пользователя
 * @property string $avatar_path Путь до аватара пользователя в рамках Amazon S3
 * @property string $avatar_url Ссылка на аватар пользователя
 * @property string $url Ссылка на пользователя
 * @property string $full_name ФИО пользователя
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
 */
class User extends Authenticatable
{

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
        'nickname', 'email', 'password',
        'surname', 'patronymic', 'birthday_date',
        'avatar', 'gender',
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
     * Установить аватар для пользователя
     *
     * @param UploadedFile $avatar Аватар пользователя
     * @param bool $save Сохранять ли состояние модели после записи
     * @return bool
     * @throws Exception
     */
    public function setAvatar(UploadedFile $avatar, bool $save = false) : bool
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

        return true;
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

        return '/img/' . ($this->gender == $this::GENDER_FEMALE ? 'default_avatar_woman.jpg' : 'default_avatar_man.jpg');
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
        if (blank($this->name) && blank($this->surname) && blank($this->patronymic)) {
            return 'Пользователь';
        }
        return $this->surname . ' ' . $this->name . ' ' . $this->patronymic;
    }
}
