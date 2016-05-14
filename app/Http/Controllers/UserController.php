<?php

namespace App\Http\Controllers;

use App\Profile;
use Auth;
use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\User;
use Illuminate\Support\Str;

class UserController extends Controller
{
    //Создание нового пользователя
    public function registration(Request $request)
    {
        //Проверяем данные для пользователя
        $user = new User();
        if ($request->has('email')) {
            $request['email'] = mb_strtolower($request->email);
        }

        if ($user->validate($request->all())) {
            $profile = new Profile();
            if ($profile->validate($request->all())) {

                $user = new User($request->all());
                $user->password = bcrypt($request['password']);
                $user->email_verify_code = bcrypt(Str::random(32));
                $user->email_change_code = bcrypt(Str::random(32));
                $user->save();
                $profile = new Profile($request->all());
                $user->profile()->save($profile);

                Auth::loginUsingId($user->id);
                $user->sendEmailVerify();

                return response($this->buildResponse('success', trans('messages.registration_success')), 200)
                    ->header('Content-Type', 'text/json');
            } else {
                return response($this->buildResponse('error', $profile->errors()), 400)
                    ->header('Content-Type', 'text/json');
            }
        } else {
            return response($this->buildResponse('error', $user->errors()), 400)
                ->header('Content-Type', 'text/json');
        }
    }

    //Проверяем верификацию пользователя по ссылке
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

    //Запрос на повторную верификацию по ссылке
    public function sendEmailVerify()
    {
        $user = Auth::user();
        if (!$user->email_verify) {
            $user->sendEmailVerify();
            return response($this->buildResponse('success', trans('messages.send_email_verify_success')), 200)
                ->header('Content-Type', 'text/json');
        } else {
            return response($this->buildResponse('error', trans('messages.send_email_verify_error')), 409)
                ->header('Content-Type', 'text/json');
        }
    }

    //Аутентификация
    public function auth(Request $request)
    {
        if ($request->has('login') && $request->has('password')) {
            $request['email'] = mb_strtolower($request->login);

            $user = User::where('email', $request['email'])->first();
            if (!is_null($user)) {
                if (password_verify($request->password, $user->password)) {
                    if ($request->has('remember')) {
                        Auth::login($user, (bool) $request->remember);
                        return response($this->buildResponse('success', trans('messages.auth_success')), 200)
                            ->header('Content-Type', 'text/json');
                    } else {
                        Auth::login($user);
                        return response($this->buildResponse('success', trans('messages.auth_success')), 200)
                            ->header('Content-Type', 'text/json');
                    }
                } else {
                    return response($this->buildResponse('error', trans('messages.auth_bad_password')), 400)
                        ->header('Content-Type', 'text/json');
                }
            } else {
                return response($this->buildResponse('error', trans('messages.auth_user_not_found')), 400)
                    ->header('Content-Type', 'text/json');
            }
        } else {
            return response($this->buildResponse('error', trans('messages.auth_not_enter_login_and_password')), 400)
                ->header('Content-Type', 'text/json');
        }
    }

    //Деутентификация
    public function unauth()
    {
        Auth::logout();
        return response($this->buildResponse('success', trans('messages.unauth_success')), 200)
            ->header('Content-Type', 'text/json');
    }

    //Запрос на смену пароля
    public function resetPasswordRequest(Request $request)
    {
        if ($request->has('email')) {
            $request['email'] = mb_strtolower($request->email);

            $user = User::where('email', $request->email)->where('email_verify', true)->first();
            if (!is_null($user)) {
                $user->resetPasswordRequest();
                return response($this->buildResponse('success', trans('messages.reset_password_request_success')), 200)
                    ->header('Content-Type', 'text/json');
            } else {
                return response($this->buildResponse('error', trans('messages.reset_password_request_email_not_found')), 400)
                    ->header('Content-Type', 'text/json');
            }
        } else {
            return response($this->buildResponse('error', trans('messages.reset_password_request_email_not_enter')), 400)
                ->header('Content-Type', 'text/json');
        }
    }

    //Смена пароля
    public function resetPassword(Request $request)
    {
        if ($request->has('email') && $request->has('code')) {
            $user = User::where('email', $request->email)->where('reset_code', $request->code)->first();
            if (!is_null($user)) {
                $user->resetPassword();
                //TODO: нормальный шаблон
                //TODO: а что здесь собственно покызвать? Главную страницу с попапом?
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

    //Запрос на смену email
    public function changeEmailRequest(Request $request)
    {
        $user = Auth::user();
        if($user->email_verify) {
            if ($request->has('email')) {
                $request['email'] = mb_strtolower($request->email);
            }
            if ($user->validateEmail($request->all())) {
                $user->new_email = $request->email;
                $user->save();
                $user->changeEmailRequest();
                return response($this->buildResponse('success', trans('messages.change_email_request_success')), 200)
                    ->header('Content-Type', 'text/json');

            } else {
                return response($this->buildResponse('error', $user->errors()), 400)
                    ->header('Content-Type', 'text/json');
            }
        } else {
            return response($this->buildResponse('error', trans('messages.change_email_request_not_verify')), 403)
                ->header('Content-Type', 'text/json');
        }
    }

    //Смена email
    public function changeEmail(Request $request)
    {
        if ($request->has('email') && $request->has('code')) {
            $user = User::where('new_email', $request->email)->where('email_change_code', $request->code)->first();
            if (!is_null($user)) {
                //проверяем на уникальность
                if ($user->validateEmail($request->all())) {
                    $user->changeEmail();
                    //TODO: нормальный шаблон
                    //TODO: а что здесь собственно покызвать? Главную страницу с попапом?
                    echo 'Ваш email был успешно изменён';
                } else {
                    //TODO: нормальный шаблон
                    echo 'пшёл вон отседова';
                }
            } else {
                //TODO: нормальный шаблон
                echo 'пшёл вон отседова';
            }
        } else {
            //TODO: нормальный шаблон
            echo 'пшёл вон отседова';
        }
    }

    //Заполнение стандартных значений профиля
    /*
     * nickname (заполняем тоже через профиль)
     * name
     * surname
     * patronymic
     * birthday
     * gender
     */
    public function fillingProfile(Request $request)
    {
        //Проверяем данные для профиля
        $user = Auth::user();
        if ($user->profile->validate($request->all())) {
            if ($request->has('nickname')) {
                $user->nickname = $request->nickname;
                $user->save();
            }
            $user->profile->fill($request->all());
            $user->profile->save();
            return response($this->buildResponse('success', trans('messages.filling_profile_success')), 200)
                ->header('Content-Type', 'text/json');
        } else {
            return response($this->buildResponse('error', $user->profile->errors()), 400)
                ->header('Content-Type', 'text/json');
        }
    }

    //Смена пароля
    public function changePassword(Request $request)
    {
        //TODO: создать в middleware глобальную переменную для авторизованного пользователя
        $user = Auth::user();
        if ($user->validatePassword($request->all())) {
            if (password_verify($request->old_password, $user->password)) {
                $user->password = $request->password;
                $user->save();
                return response($this->buildResponse('success', trans('messages.change_password_success')), 200)
                    ->header('Content-Type', 'text/json');
            } else {
                return response($this->buildResponse('error', trans('messages.change_password_not_match')), 400)
                    ->header('Content-Type', 'text/json');
            }
        } else {
            return response($this->buildResponse('error', $user->errors()), 400)
                ->header('Content-Type', 'text/json');
        }

    }

    //Загрузка нового аватара
    public function uploadAvatar(Request $request)
    {
        $user = Auth::user();
        if ($user->profile->validateAvatar($request->all())) {
            $user->profile->saveAvatar($request->file('avatar'));
            return response($this->buildResponse('success', trans('messages.upload_avatar_success')), 200)
                ->header('Content-Type', 'text/json');
        } else {
            return response($this->buildResponse('error', $user->profile->errors()), 400)
                ->header('Content-Type', 'text/json');
        }
    }

    //Смена языка
    public function changeLanguage(Request $request)
    {
        //TODO: найти ещё какой-нибудь способ искать языки
        if ($this->validateLanguage($request->all())) {
            \App::setLocale($request->language);
            setcookie('locale', $request->language, time()*2, '/');
            return response($this->buildResponse('success', trans('messages.change_language_success')), 200)
                ->header('Content-Type', 'text/json');
        } else {
            return response($this->buildResponse('error', $this->errors()), 400)
                ->header('Content-Type', 'text/json');
        }

    }

    private function validateLanguage($request)
    {
        $v = Validator::make($request, $this->rulesLanguage);

        if ($v->fails()) {
            array_push($this->errors, $v->errors());
            return false;
        } else {
            return true;
        }
    }

    private function errors()
    {
        return $this->errors;
    }

    private $rulesLanguage = [
        'language' => 'required|language',
    ];

    private $errors = [];
}
