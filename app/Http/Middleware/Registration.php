<?php

namespace App\Http\Middleware;

use Closure;
use \Validator;

class Registration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email|unique:users',
                'password'  => 'required|confirmed|min:6'
            ]
        );

        if ($validator->fails()) {
            // Переданные данные не прошли проверку
            return response($this->parseError($validator->failed()), 400)
                ->header('Content-Type', 'text/json');
        } else {
            return $next($request);
        }
    }

    protected function parseError($errors)
    {
        $_errors = [];
        if (isset($errors['email'])){
            foreach($errors['email'] as $key => $error){
                switch($key){
                    case 'Required':
                        $_errors[]['text'] = 'Пожалуйста, не пытайтесь обмануть систему.';
                        break;
                    case 'Email':
                        $_errors[]['text'] = 'К сожалению, введеный Вами  адрес электронной почты некорректен. Пожалуйста, исправьте ошибки и попробуйте снова. Мы верим в Вас - все получится!';
                        break;
                    case 'Unique':
                        $_errors[]['text'] = 'Указанный Вами email уже существует в нашей базе. Вам нужно восстановить пароль?';
                        break;
                }
            }
        }
        if (isset($errors['password'])){
            foreach($errors['password'] as $key => $error){
                switch($key){
                    case 'Required':
                        $_errors[]['text'] = 'Пожалуйста, не пытайтесь обмануть систему.';
                        break;
                    case 'Confirmed':
                        $_errors[]['text'] = 'К сожалению, введеные Вами пароли не совпадают.';
                        break;
                    case 'Min':
                        $_errors[]['text'] = 'Пароль не может быть короче шести символов.';
                        break;
                }
            }
        }

        $json['status'] = 'error';
        $json['code'] = 400;
        $json['property'] = $_errors;

        return json_encode($json);
    }
}
