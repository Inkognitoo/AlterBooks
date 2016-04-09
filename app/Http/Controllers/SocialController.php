<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Oauth;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Log;
use Mail;
use Socialite;

//TODO: очень много if-ов, подумать, как это можно отрефакторить
class SocialController extends Controller {

    public function __construct(){
        $this->middleware('guest');
    }

    public function socialAuth($provider = null)
    {
        if(!config('services.'.$provider)) abort('404');

        return Socialite::driver($provider)->redirect();
    }

    public function socialAuthCallback($provider = null)
    {
        if(!config('services.'.$provider)) abort('404');

        try {
            if ($social_user = Socialite::driver($provider)->user()) {
                $oauth = Oauth::where('oauth_id', $social_user->id)->where('provider', $provider)->first();

                //проверяем, регистрируется пользователь или авторизуется
                if ($oauth === null) {
                    //пытаемся зарегистрировать пользователя
                    //проверяем email на уникальность и нe null
                    if ($social_user->email !== null && User::where('email', $social_user->email)->first() === null) {
                        //приступаем к регистрации пользователя
                        $user = $this->createUser($social_user, $provider);
                        if ($user) {
                            $oauth = new Oauth();
                            $oauth->oauth_id = $social_user->id;
                            $oauth->provider = $provider;
                            $user->oauth()->save($oauth);
                            Auth::loginUsingId($user->id);
                            //TODO: посылать email для подтверждения почты асинхронно
                            Mail::queue('auth.emails.verify',
                                [
                                    'email' => $user->email,
                                    'email_verify_code' => $user->email_verify_code,
                                ],
                            function($message) use ($user)
                            {
                                $message->to($user->email)->subject('Подтвердите Ваш email');
                            });

                            return response($this->buildResponse('success', 'Пользователь успешно зарегистрирован'), 200)
                                ->header('Content-Type', 'text/json');
                        } else {
                            return response($this->buildResponse('error', 'Не удалось обработать данные из социальной сети'), 500)
                                ->header('Content-Type', 'text/json');
                        }
                    } else {
                        //сериализуем и сохраняем в сессию данные из соцсетей
                        $serialized_social_user = Oauth::serializeForSession($social_user, $provider);
                        if ($serialized_social_user) {
                            session(['social_user' => json_encode($serialized_social_user)]);
                            return response($this->buildResponse('error', 'Указанный email уже занят'), 402)
                                ->header('Content-Type', 'text/json');
                        } else {
                            return response($this->buildResponse('error', 'Не удалось обработать данные из социальной сети'), 500)
                                ->header('Content-Type', 'text/json');
                        }
                    }
                } else {
                    //авторизуем пользователя
                    Auth::loginUsingId($oauth->user->id);

                    return response($this->buildResponse('success', 'Пользователь успешно авторизирован'), 200)
                        ->header('Content-Type', 'text/json');
                }
            } else {
                //что-то пошло не так
                return response($this->buildResponse('error', 'Не удалось получить данные из социальной сети'), 500)
                    ->header('Content-Type', 'text/json');
            }
        } catch (\Exception $e) {
            /* не знаю, какого чёрта валится именно исключение при отказе отдать свои данные, вероятно,
               косяк самих разработчиков Socialite.
            */
            //херово, но сюда может свалиться любая ошибка
            Log::error('Не удалось получить данные от соц. сети: '.$e);
            return response($this->buildResponse('error', 'Не удалось получить данные из социальной сети'), 500)
                ->header('Content-Type', 'text/json');
        }
    }

    public function enterEmail(Request $request)
    {
        if ($request->session()->has('social_user')) {
            $oauth =  new Oauth();
            if ($oauth->validate($request->all())) {
                $social_user = json_decode($request->session()->pull('social_user'), true);
                $social_user['email'] = $request['email'];
                $user = $this->createUser($social_user);
                if ($user) {
                    $oauth = new Oauth();
                    $oauth->oauth_id = $social_user['oauth_id'];
                    $oauth->provider = $social_user['provider'];
                    $user->oauth()->save($oauth);
                    Auth::loginUsingId($user->id);
                    //TODO: посылать email для подтверждения почты асинхронно
                    Mail::queue('auth.emails.verify',
                        [
                            'email' => $user->email,
                            'email_verify_code' => $user->email_verify_code,
                        ],
                        function($message) use ($user)
                        {
                            $message->to($user->email)->subject('Подтвердите Ваш email');
                        });

                    return response($this->buildResponse('success', 'Пользователь успешно зарегистрирован'), 200)
                        ->header('Content-Type', 'text/json');
                } else {
                    return response($this->buildResponse('error', 'Не удалось обработать данные из социальной сети'), 500)
                        ->header('Content-Type', 'text/json');
                }
            } else {
                return response($this->buildResponse('error', $oauth->errors()), 402)
                    ->header('Content-Type', 'text/json');
            }
        } else {
            return response($this->buildResponse('error', 'Forbidden'), 403)
                ->header('Content-Type', 'text/json');
        }
    }

    private function createUser($social_user, $provider = null)
    {
        $oauth = new Oauth();
        if (is_array($social_user)) {
            return $oauth->createUser($social_user);
        } else {
            $social_user = Oauth::serializeForSession($social_user, $provider);
            return $oauth->createUser($social_user);
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