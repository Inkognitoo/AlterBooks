<?php

namespace App\Http\Controllers\Api;

use App\Http\Middleware\IsUserNotAuth;
use App\Models\User;
use Auth;
use App\Http\Controllers\Controller;
use App\Exceptions\ApiException;
use App\Http\Middleware\Api\ApiWrapper;
use App\Http\Requests\ReviewCreateRequest;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(IsUserNotAuth::class)->only('validate');

        $this->middleware(ApiWrapper::class);
    }

    /**
     * Валидируем запрос
     *
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function validator(Request $request)
    {
        $response = [
            'success' => true,
            'data' => null,
            'errors' => [],
        ];

        switch ($request->name) {
            case 'email':
                if (User::where('email', $request->value)->first() !== null) {
                    $response = [
                        'success' => false,
                        'data' =>  $request->name,
                        'errors' => ['Пользователь с таким адресом уже существует'],
                    ];
                }
                break;
            case 'password':
                if (mb_strlen($request->value) < 6) {
                    $response = [
                        'success' => false,
                        'data' =>  $request->name,
                        'errors' => ['Пароль менее 6 символов'],
                    ];
                }
                break;
            case 'nickname':
                if (User::where('nickname', $request->value)->first() !== null) {
                    $response = [
                        'success' => false,
                        'data' =>  $request->name,
                        'errors' => ['Этот псевдоним уже занят'],
                    ];
                }
                break;
        }

        return $response;
    }
}
