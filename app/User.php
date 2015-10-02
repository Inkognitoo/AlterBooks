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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function registration() {
        $this->password = Hash::make($this->password);
        $this->activation_code = $this->generateCode();
        $this->is_active = false;
        $this->save();

        Log::info("User [{$this->email}, {$this->id}] registered. Activation code: {$this->activation_code}");

        $this->sendActivationMail();
    }

    protected function generateCode() {
        return Str::random(); // По умолчанию длина случайной строки 16 символов
    }

    protected function sendActivationMail() {
        //генерируем ссылку
        $activationUrl = Request::root().'api/v1/activate?email='.$this->email.'&activation_code='.$this->activation_code;

        //TODO: замутить нормальные очереди
        Mail::send('emails/activation',
            ['activationUrl' => $activationUrl],
            function ($message){
                $message->to($this->email)->subject('Спасибо за регистрацию!');
            }
        );
    }

    public function activate($activation_code) {
        // Если пользователь уже активирован, не будем делать никаких
        // проверок и вернем false
        if ($this->is_active) {
            return false;
        }

        // Если коды не совпадают, то также ввернем false
        if ($activation_code != $this->activation_code) {
            return false;
        }

        // Обнулим код, изменим флаг isActive и сохраним
        $this->activation_code = '';
        $this->is_active = true;
        $this->save();

        // И запишем информацию в лог, просто, чтобы была :)
        Log::info("User [{$this->email}] successfully activated");

        return true;
    }
}
