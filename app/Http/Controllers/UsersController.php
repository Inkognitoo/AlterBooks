<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    public function registration(Request $request) {
        // Сама регистрация с уже проверенными данными
        $user = new User();
        $user->fill($request->input());
        $user->registration();

        // Вывод информационного сообщения об успешности регистрации
        $json['status'] = 'OK';
        $json['code'] = 200;
        $json['property'] = ['Регистрация почти завершена. Вам необходимо подтвердить e-mail, указанный при регистрации, перейдя по ссылке в письме.'];

        return response(json_encode($json), 200)
            ->header('Content-Type', 'text/json');
    }

    public function getActivate($userId, $activationCode) {
        // Получаем указанного пользователя
        $user = User::find($userId);
        if (!$user) {
            return $this->getMessage("Неверная ссылка на активацию аккаунта.");
        }

        // Пытаемся его активировать с указанным кодом
        if ($user->activate($activationCode)) {
            // В случае успеха авторизовываем его
            Auth::login($user);
            // И выводим сообщение об успехе
            return $this->getMessage("Аккаунт активирован", "/");
        }

        // В противном случае сообщаем об ошибке
        return $this->getMessage("Неверная ссылка на активацию аккаунта, либо учетная запись уже активирована.");
    }

    public function login(Request $request) {
        // Формируем базовый набор данных для авторизации
        // (isActive => 1 нужно для того, чтобы аторизоваться могли только
        // активированные пользователи)
        $creds = array(
            'password' => $request->input('password'),
            'is_active'  => 1,
        );

        // В зависимости от того, что пользователь указал в поле username,
        // дополняем авторизационные данные
        $username = $request->input('username');
        if (strpos($username, '@')) {
            $creds['email'] = $username;
        } else {
            $creds['username'] = $username;
        }

        // Пытаемся авторизовать пользователя
        if (Auth::attempt($creds, $request->has('remember'))) {
            Log::info("User [{$username}] successfully logged in.");
            return redirect()->intended();
        } else {
            Log::info("User [{$username}] failed to login.");
        }

        $alert = "Неверная комбинация имени (email) и пароля, либо учетная запись еще не активирована.";

        // Возвращаем пользователя назад на форму входа с временной сессионной
        // переменной alert (withAlert)
        return redirect()->back()->withAlert($alert);
    }
}
