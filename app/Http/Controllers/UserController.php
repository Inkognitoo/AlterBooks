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

    public function auth()
    {

    }

    public function resetPasswordRequest(Request $request)
    {
        if ($request->has('email')) {
            $user = User::where('email', $request->email)->first();
            if ($user !== null) {
                $user->resetPasswordRequest();
                return response($this->buildResponse('success', 'Код восстановления был выслан на указанный email'), 200)
                    ->header('Content-Type', 'text/json');
            } else {
                return response($this->buildResponse('error', 'Указаного email не существует в базе'), 402)
                    ->header('Content-Type', 'text/json');
            }
        } else {
            return response($this->buildResponse('error', 'Необходимо указать email'), 402)
                ->header('Content-Type', 'text/json');
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
