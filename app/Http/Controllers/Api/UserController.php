<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Validator;

class UserController extends Controller
{

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|exists:users,email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            $errors = (new ValidationException($validator))->errors();

            $response = [
                'success' => false,
                'data' => null,
                'errors' => $errors,
            ];
            return response()->json($response, 400);
        }

        if (!$this->attemptLogin($request)) {
            $response = [
                'success' => false,
                'data' => null,
                'errors' => [
                    'email' => 'Имя пользователя и пароль не совпадают.'
                ],
            ];
            return response()->json($response, 400);
        }

        $response = [
            'success' => true,
            'data' => 'Успешная авторизация',
            'errors' => [],
        ];

        return response()->json($response, 200);
    }

}
