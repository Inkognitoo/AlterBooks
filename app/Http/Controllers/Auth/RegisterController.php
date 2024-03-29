<?php

namespace App\Http\Controllers\Auth;

use App\Rules\CaseInsensitiveUnique;
use App\Rules\Nickname;
use App\User;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login.
     *
     * @return string
     */
    protected function redirectTo()
    {
        return Auth::user()->url;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nickname' => [
                'required', 'string', 'max:255',
                new CaseInsensitiveUnique('users'),
                new Nickname(),
            ],
            'email' => [
                'required', 'string', 'email', 'max:255',
                new CaseInsensitiveUnique('users'),
            ],
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = new User();
        $user->nickname = $data['nickname'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->api_token = str_random(60);
        $user->save();

        return $user;
    }
}
