<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Oauth;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Log;
use Mail;
use Laravel\Socialite\Facades\Socialite;


class SocialController extends Controller {

    public function __construct(){
        //TODO: нужно ли оно вообще?
        //$this->middleware('guest');
    }

    public function socialAuth($provider = null)
    {
        if(!config('services.'.$provider)) {
            return response($this->buildResponse('error', trans('messages.social_auth_provider_not_found')), 400)
                ->header('Content-Type', 'text/json');
        } else {
            return Socialite::with($provider)->redirect();
        }
    }

    public function socialAuthCallback($provider = null)
    {

        //TODO: нормальное сообщение
        if(!config('services.'.$provider)) abort('404');

        try {
            if ($social_user = Socialite::with($provider)->user()) {
                $oauth = Oauth::where('oauth_id', $social_user->id)->where('provider', $provider)->first();

                //проверяем, регистрируется пользователь или авторизуется
                if (is_null($oauth)) {
                    //пытаемся зарегистрировать пользователя
                    //проверяем email на уникальность и нe null
                    if (!is_null($social_user->email)) {
                        $social_user->email = mb_strtolower($social_user->email);
                    }
                    if ($social_user->email !== null && User::where('email', $social_user->email)->first() === null) {
                        //приступаем к регистрации пользователя
                        $user = $this->createUser($social_user, $provider);
                        if ($user) {
                            Auth::loginUsingId($user->id);
                            $user->sendEmailVerify();
                            return response($this->buildResponse('success', trans('messages.social_auth_callback_registration_success')), 200)
                                ->header('Content-Type', 'text/json');
                        } else {
                            return response($this->buildResponse('error', trans('messages.social_auth_callback_not_process_date')), 500)
                                ->header('Content-Type', 'text/json');
                        }
                    } else {
                        //сериализуем и сохраняем в сессию данные из соцсетей
                        $serialized_social_user = Oauth::serializeForSession($social_user, $provider);
                        if ($serialized_social_user) {
                            session(['social_user' => json_encode($serialized_social_user)]);
                            return response($this->buildResponse('error', trans('messages.social_auth_callback_email_in_use')), 409)
                                ->header('Content-Type', 'text/json');
                        } else {
                            return response($this->buildResponse('error', trans('messages.social_auth_callback_not_process_date')), 500)
                                ->header('Content-Type', 'text/json');
                        }
                    }
                } else {
                    //авторизуем пользователя
                    Auth::loginUsingId($oauth->user->id);

                    return response($this->buildResponse('success', trans('messages.social_auth_callback_auth_success')), 200)
                        ->header('Content-Type', 'text/json');
                }
            } else {
                //что-то пошло не так
                return response($this->buildResponse('error', trans('messages.social_auth_callback_not_giving_date')), 500)
                    ->header('Content-Type', 'text/json');
            }
        } catch (\Exception $e) {
            /* не знаю, какого чёрта валится именно исключение при отказе отдать свои данные, вероятно,
               косяк самих разработчиков Socialite.
            */
            //херово, но сюда может свалиться любая ошибка
            Log::error('Не удалось получить данные от соц. сети: '.$e);
            return response($this->buildResponse('error', trans('messages.social_auth_callback_not_giving_date')), 500)
                ->header('Content-Type', 'text/json');
        }
    }

    public function enterEmail(Request $request)
    {
        if ($request->session()->has('social_user')) {
            $oauth = new Oauth();
            if ($request->has('email')) {
                $request['email'] = mb_strtolower($request->email);
            }
            if ($oauth->validate($request->all())) {
                $social_user = json_decode($request->session()->pull('social_user'), true);
                $social_user['email'] = $request['email'];
                $user = $this->createUser($social_user);
                if ($user) {
                    Auth::loginUsingId($user->id);
                    $user->sendEmailVerify();

                    return response($this->buildResponse('success', trans('messages.enter_email_success')), 200)
                        ->header('Content-Type', 'text/json');
                } else {
                    return response($this->buildResponse('error', trans('messages.enter_email_not_process_date')), 500)
                        ->header('Content-Type', 'text/json');
                }
            } else {
                return response($this->buildResponse('error', $oauth->errors()), 400)
                    ->header('Content-Type', 'text/json');
            }
        } else {
            return response($this->buildResponse('error', trans('messages.enter_email_forbidden')), 401)
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
}