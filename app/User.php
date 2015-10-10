<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Request;

/**
 * Class User
 * @package App
 *
 * @property integer $id User id
 * @property string $email Flag user activations
 * @property string $password User password
 * @property string $activation_code Code for activation user
 * @property boolean $active Flag user activations
 *
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{

    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    public function registration() {
        $this->password = Hash::make($this->password);
        $this->activation_code = $this->generateCode();
        $this->active = false;
        $this->save();
        $this->profile()->save(new Profile());
        $this->save();

        Log::info("User [{$this->email}, {$this->id}] registered. Activation code: {$this->activation_code}");

        $this->sendActivationMail();
    }

    protected function generateCode() {
        return Str::random(); // По умолчанию длина случайной строки 16 символов
    }

    protected function sendActivationMail() {
        //генерируем ссылку
        $activationUrl = Request::root().'/api/v1/activate/'.$this->id.'/'.$this->activation_code;

        //и отправляем (в очереди)
        Mail::queue('emails/activation',
            ['activationUrl' => $activationUrl],
            function($message) {
                $message->to($this->email)->subject('Спасибо за регистрацию! Активируйте Ваш аккаунт.');
        });
    }

    public function activate($activation_code) {
        // Если пользователь уже активирован, не будем делать никаких
        // проверок и вернем false
        if ($this->active) {
            return false;
        }

        // Если коды не совпадают, то также ввернем false
        if ($activation_code != $this->activation_code) {
            return false;
        }

        // Обнулим код, изменим флаг isActive и сохраним
        $this->activation_code = null;
        $this->active = true;
        $this->save();

        // И запишем информацию в лог, просто, чтобы была :)
        Log::info("User [{$this->email}] successfully activated");

        return true;
    }
}
