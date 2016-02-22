<?php

namespace App\Http\Controllers;

use App\Profile;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
                $user->save();
                $profile = new Profile($request->all());
                $user->profile()->save($profile);

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

    private function buildResponse($status, $payload)
    {
        return json_encode([
            'status' => $status,
            'payload' => $payload
        ]);
    }

}
