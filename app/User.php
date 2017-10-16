<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Storage;

/**
 * App\User
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Book[] $books
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Book[] $libraryBooks
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
 * @property string|null $avatar
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
     * Получить url аватары пользователя
     *
     * @return string
     */
    public function getAvatarUrl(): string
    {
        if (!empty($this->avatar)) {
            return Storage::disk('s3')->url('avatars/' . $this->id . '/' . $this->avatar);
        }

        return '/img/' . ($this->gender == $this::GENDER_FEMALE ? 'default_avatar_woman.jpg' : 'default_avatar_man.jpg');
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
}
