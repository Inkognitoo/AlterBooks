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
                'data' => 'email',
                'errors' => $errors,
            ];
            return response()->json($response, 400);
        }

        if (!$this->attemptLogin($request)) {
            $response = [
                'success' => false,
                'data' => 'password',
                'errors' => [
                    'email' => 'Имя пользователя и пароль не совпадают.'
                ],
            ];
            return response()->json($response, 400);
        }

        $user = Auth::user();
        $response = [
            'success' => true,
            'data' => $user->api_token,
            'errors' => [],
        ];

        return response()->json($response, 200);
    }

}
