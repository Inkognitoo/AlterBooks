<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Storage;

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

    public function getAvatarUrl()
    {
        if (!empty($this->avatar)) {
            return asset(Storage::url('avatars/' . $this->id . '/' . $this->avatar));
        } else {
            return '/img/' . ($this->gender == $this::GENDER_FEMALE ? 'default_avatar_woman.jpg' : 'default_avatar_man.jpg');
        }
    }
}
