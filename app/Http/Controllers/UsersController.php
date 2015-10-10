<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function registration(Request $request) {
        // Сама регистрация с уже проверенными данными
        $user = new User();
        $user->email = $request['email'];
        $user->password = $request['password'];

        $user->registration();

        // Вывод информационного сообщения об успешности регистрации
        $json = [
            'status' => 'OK',
            'code' => 200,
            'property' => [
                'text' => 'Регистрация почти завершена. Вам необходимо подтвердить e-mail, указанный при регистрации, перейдя по ссылке в письме.'
            ]
        ];

        return response(json_encode($json), 200)
            ->header('Content-Type', 'text/json');
    }

    public function activate($id, $activation_code) {
        // Получаем указанного пользователя
        $user = User::find($id);
        if (!$user) {
            $json = [
                'status' => 'Bad Request',
                'code' => 400,
                'property' => [
                    'text' => 'Неверная ссылка на активацию аккаунта.'
                ]
            ];

            return response(json_encode($json), 400)
                ->header('Content-Type', 'text/json');
        }

        // Пытаемся его активировать с указанным кодом
        if (!$user->activate($activation_code)) {
            //если не удалось, сообщаем об ошибке
            $json = [
                'status' => 'Bad Request',
                'code' => 400,
                'property' => [
                    'text' => 'Неверная ссылка на активацию аккаунта, либо учетная запись уже активирована.'
                ]
            ];

            return response(json_encode($json), 400)
                ->header('Content-Type', 'text/json');
        }

        // В случае успеха авторизовываем пользователя
        Auth::login($user);
        // И выводим сообщение об успехе
        $json = [
            'status' => 'OK',
            'code' => 200,
            'property' => [
                'text' => 'Аккаунт активирован.'
            ]
        ];

        return response(json_encode($json), 200)
            ->header('Content-Type', 'text/json');
    }

    public function login(Request $request) {
        // Формируем базовый набор данных для авторизации
        $login = array(
            'password' => $request->password,
            'active' => true,
            'email' => $request->email
        );

        // В зависимости от того, что пользователь указал в поле username,
        // дополняем авторизационные данные

        // Пытаемся авторизовать пользователя
        //TODO: подумать о специальном ответе при попытке неактивированного пользователя войти в систему
        if (!Auth::attempt($login, $request->has('remember'))) {
            $json = [
                'status' => 'Bad Request',
                'code' => 400,
                'property' => [
                    'text' => 'Неверный логин, пароль или аккаунт не ещё активирован.'
                ]
            ];

            return response(json_encode($json), 400)
                ->header('Content-Type', 'text/json');
        }

        $json = [
            'status' => 'OK',
            'code' => 200,
            'property' => [
                'text' => 'Login success'
            ]
        ];

        return response(json_encode($json), 200)
            ->header('Content-Type', 'text/json');
    }

    public function logout() {
        Auth::logout();

        $json = [
            'status' => 'OK',
            'code' => 200,
            'property' => [
                'text' => 'Logout success'
            ]
        ];

        return response(json_encode($json), 200)
            ->header('Content-Type', 'text/json');
    }
}
