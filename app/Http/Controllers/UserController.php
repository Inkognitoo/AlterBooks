<?php

namespace App\Http\Controllers;

use App\Profile;
use App\User;
use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class UserController extends Controller
{
    //
    public function registration(Request $request)
    {
        //Проверяем данные для пользователя
        $user = new User();
        if ($user->validate($request->all())) {
            $profile = new Profile();
            if ($profile->validate($request->all())) {
                $user = new User($request->all());
                $user->password = bcrypt($request['password']);
                $user->email_verify_code = bcrypt(Str::random(32));
                $user->save();
                $profile = new Profile($request->all());
                $user->profile()->save($profile);

                Auth::loginUsingId($user->id);

                return response($this->buildResponse('success', 'Пользователь успешно зарегистрирован'), 200)
                    ->header('Content-Type', 'text/json');
            } else {
                return response($this->buildResponse('error', $profile->errors()), 402)
                    ->header('Content-Type', 'text/json');
            }
        } else {
            return response($this->buildResponse('error', $user->errors()), 402)
                ->header('Content-Type', 'text/json');
        }
    }

    public function auth(Request $request)
    {
        if ($request->has('login') && $request->has('password')) {
            $user = User::where('email', $request->login)->orWhere('nickname', $request->login)->first();
            if ($user !== null) {
                if (password_verify($request->password, $user->password)) {
                    if ($request->has('remember')) {
                        Auth::login($user, (bool) $request->remember);
                        return response($this->buildResponse('success', 'пользователь успешно авторизован'), 200)
                            ->header('Content-Type', 'text/json');
                    } else {
                        Auth::login($user);
                        return response($this->buildResponse('success', 'пользователь успешно авторизован'), 200)
                            ->header('Content-Type', 'text/json');
                    }
                } else {
                    return response($this->buildResponse('error', 'Неверный пароль'), 402)
                        ->header('Content-Type', 'text/json');
                }
            } else {
                return response($this->buildResponse('error', 'Пользовтеля не существует'), 402)
                    ->header('Content-Type', 'text/json');
            }
        } else {
            return response($this->buildResponse('error', 'Необходимо указать логин и пароль'), 402)
                ->header('Content-Type', 'text/json');
        }
    }

    public function resetPasswordRequest(Request $request)
    {
        if ($request->has('email')) {
            $user = User::where('email', $request->email)->where('email_verify', true)->first();
            if ($user !== null) {
                $user->resetPasswordRequest();
                return response($this->buildResponse('success', 'Код восстановления был выслан на указанный email'), 200)
                    ->header('Content-Type', 'text/json');
            } else {
                return response($this->buildResponse('error', 'Указаного email не существует в базе либо email не был подтвержён'), 402)
                    ->header('Content-Type', 'text/json');
            }
        } else {
            return response($this->buildResponse('error', 'Необходимо указать email'), 402)
                ->header('Content-Type', 'text/json');
        }
    }

    public function resetPassword(Request $request)
    {
        if ($request->has('email') && $request->has('code')) {
            $user = User::where('email', $request->email)->where('reset_code', $request->code)->first();
            if ($user !== null) {
                $user->resetPassword();
                //TODO: нормальный шаблон
                echo 'Ваш пароль был успешно сброшен, новый пароль был отправлен на Ваш email';
            } else {
                //TODO: нормальный шаблон
                echo 'пшёл вон отседова';
            }
        } else {
            //TODO: нормальный шаблон
            echo 'пшёл вон отседова';
        }
    }

    public function emailVerify(Request $request)
    {
        if ($request->has('email') && $request->has('code')) {
            $user = User::where('email', $request->email)->where('email_verify_code', $request->code)->where('email_verify', false)->first();
            if ($user !== null) {
                $user->email_verify = true;
                $user->save();
                //TODO: нормальный шаблон
                echo 'email был успешно подтверждён! Через пять минут перекиним тебя на родину';
            } else {
                //TODO: нормальный шаблон
                echo 'пшёл вон отседова';
            }
        } else {
            //TODO: нормальный шаблон
            echo 'пшёл вон отседова';
        }
    }

    private function buildResponse($status, $payload)
    {
        return json_encode([
            'status' => $status,
            'payload' => $payload
        ]);
    }

}
