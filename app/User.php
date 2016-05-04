<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Mail;
use Validator;
use Storage;
/**
 * App\User
 *
 * @property integer $id
 * @property string $nickname
 * @property string $email
 * @property string $password
 * @property string $reset_code
 * @property boolean $email_verify
 * @property string $email_verify_code
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Profile $profile
 * @property-read \App\Oauth $oauth
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereNickname($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereResetCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmailVerify($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmailVerifyCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUpdatedAt($value)
 */
class User extends Authenticatable
{
    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    public function oauth()
    {
        return $this->hasOne('App\Oauth');
    }
//
//    public function book()
//    {
//        return $this->hasMany('App\Book', 'user_id', 'author_id');
//    }

    public function resetPasswordRequest()
    {
        //генерируем код восстановления
        $this->reset_code = bcrypt(Str::random(32));
        $this->save();
        //посылаем его пользователю по email
        //TODO: делать это асинхронно
        $user = $this;
        Mail::queue('emails.reset_password_request',
            [
                'email' => $user->email,
                'reset_code' => $user->reset_code,
            ],
            function($message) use ($user)
            {
                $message->to($user->email)->subject('Сброс пароля на AlterBooks');
            });
    }

    public function resetPassword()
    {
        $this->reset_code = null;
        $password = Str::random(10);
        $this->password = bcrypt($password);
        $this->save();
        //посылаем его пользователю по email
        //TODO: делать это асинхронно
        $user = $this;
        Mail::queue('emails.new_password',
            [
                'password' => $password,
            ],
            function($message) use ($user)
            {
                $message->to($user->email)->subject('Сброс пароля на AlterBooks');
            });
    }

    public function sendEmailVerify()
    {
        $user = $this;
        $user->email_verify_code = bcrypt(Str::random(32));
        $user->save();
        //TODO: посылать email для подтверждения почты асинхронно
        Mail::queue('auth.emails.verify',
            [
                'email' => $user->email,
                'email_verify_code' => $user->email_verify_code,
            ],
            function($message) use ($user)
            {
                $message->to($user->email)->subject('Подтвердите Ваш email');
            });
    }

    public function changeEmailRequest()
    {
        $user = $this;
        $user->email_change_code = bcrypt(Str::random(32));
        $user->save();
        //TODO: посылать email для подтверждения почты асинхронно
        Mail::queue('emails.change_email',
            [
                'email' => $user->new_email,
                'email_change_code' => $user->email_change_code,
            ],
            function($message) use ($user)
            {
                $message->to($user->new_email)->subject('Подтвердите Ваш новый email');
            });
    }

    public function changeEmail()
    {
        $user = $this;
        //TODO: посылать email для уведомления асинхронно
        Mail::queue('emails.change_email_success',
            [
                'email' => $user->new_email
            ],
            function($message) use ($user)
            {
                $message->to($user->email)->subject('Вы успешно сменили email');
            });
        $user->email_change_code = bcrypt(Str::random(32));
        $user->email = $user->new_email;
        $user->new_email = null;
        $user->save();
    }

    public function saveAvatar(UploadedFile $avatar)
    {
        //удаляем старый аватар если он есть
        if (!is_null($this->profile->avatar)) {
            Storage::delete("avatars/{$this->id}/{$this->profile->avatar}");
        }

        //если нужно, создаём необходимую директорию
        Storage::makeDirectory("avatars/{$this->id}");

        //сохраняем новый аватар
        $avatar_name = Str::random(32).'.'.$avatar->getClientOriginalExtension();
        $avatar->move(storage_path("app/avatars/{$this->id}"), $avatar_name);
        $this->profile->avatar = $avatar_name;
        $this->profile->save();
    }

    public function validate($request)
    {
        $v = Validator::make($request, $this->rules);

        if ($v->fails())
        {
            $this->errors = $v->errors();
            return false;
        }

        return true;
    }

    public function validateEmail($request)
    {
        $v = Validator::make($request, $this->rulesEmail);

        if ($v->fails())
        {
            $this->errors = $v->errors();
            return false;
        }

        return true;
    }

    public function validatePassword($request)
    {
        $v = Validator::make($request, $this->rulesPassword);

        if ($v->fails())
        {
            $this->errors = $v->errors();
            return false;
        }

        return true;
    }

    public function errors()
    {
        return $this->errors;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nickname', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //TODO: regexp ограничение для ника
    /*
     * не является одним из списка зарезервированных
     * не id[number]
     * содержит только цифры, буквы, - и _
     */
    private $rules = [
        'nickname' => 'required|max:255|unique:users',
        'email' => 'required|email|max:255|unique:users',
        'password' => 'required|confirmed|min:6|max:255',
        'password_confirmation' => 'required|min:6|max:255'
    ];

    private $rulesEmail = [
        'email' => 'required|email|max:255|unique:users'
    ];

    private $rulesPassword = [
        'old_password' => 'required|min:6|max:255',
        'password' => 'required|confirmed|min:6|max:255',
        'password_confirmation' => 'required|min:6|max:255'
    ];

    private $errors;
}