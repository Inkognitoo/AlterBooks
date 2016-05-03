<?php

namespace App\Http\Controllers;

use App\Profile;
use Auth;
use Illuminate\Http\Request;

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

                return response($this->buildResponse('success', 'Регистрация почти завершена. Вам необходимо подтвердить email, указанный при регистрации, перейдя по ссылке в письме.'), 200)
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
            return response($this->buildResponse('success', 'Новый запрос на подтверждение почты был успешно отправлен'), 200)
                ->header('Content-Type', 'text/json');
        } else {
            return response($this->buildResponse('error', 'Ваш email уже прошёл верификацию'), 409)
                ->header('Content-Type', 'text/json');
        }
    }

    //Аутентификация
    public function auth(Request $request)
    {
        if ($request->has('login') && $request->has('password')) {
            $user = User::where('email', $request->login)->first();
            if ($user !== null) {
                if (password_verify($request->password, $user->password)) {
                    if ($request->has('remember')) {
                        Auth::login($user, (bool) $request->remember);
                        return response($this->buildResponse('success', 'Пользователь успешно авторизован'), 200)
                            ->header('Content-Type', 'text/json');
                    } else {
                        Auth::login($user);
                        return response($this->buildResponse('success', 'Пользователь успешно авторизован'), 200)
                            ->header('Content-Type', 'text/json');
                    }
                } else {
                    return response($this->buildResponse('error', 'Неверный пароль'), 400)
                        ->header('Content-Type', 'text/json');
                }
            } else {
                return response($this->buildResponse('error', 'Пользовтеля не существует'), 400)
                    ->header('Content-Type', 'text/json');
            }
        } else {
            return response($this->buildResponse('error', 'Необходимо указать логин и пароль'), 400)
                ->header('Content-Type', 'text/json');
        }
    }

    //Деутентификация
    public function unauth()
    {
        Auth::logout();
        return response($this->buildResponse('success', 'Сессия пользователя была успешно прекращена'), 200)
            ->header('Content-Type', 'text/json');
    }

    //Запрос на смену пароля
    public function resetPasswordRequest(Request $request)
    {
        if ($request->has('email')) {
            $user = User::where('email', $request->email)->where('email_verify', true)->first();
            if ($user !== null) {
                $user->resetPasswordRequest();
                return response($this->buildResponse('success', 'Код восстановления был выслан на указанный email'), 200)
                    ->header('Content-Type', 'text/json');
            } else {
                return response($this->buildResponse('error', 'Указаного email не существует в базе либо email не был подтвержён'), 400)
                    ->header('Content-Type', 'text/json');
            }
        } else {
            return response($this->buildResponse('error', 'Необходимо указать email'), 400)
                ->header('Content-Type', 'text/json');
        }
    }

    //Смена пароля
    public function resetPassword(Request $request)
    {
        if ($request->has('email') && $request->has('code')) {
            $user = User::where('email', $request->email)->where('reset_code', $request->code)->first();
            if ($user !== null) {
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
            //TODO: это лишняя проверка?
            if ($request->has('email')) {
                if ($user->validateEmail($request->all())) {
                    $user->new_email = $request->email;
                    $user->save();
                    $user->changeEmailRequest();
                    return response($this->buildResponse('success', 'Запрос на смену email успешно отправлен'), 200)
                        ->header('Content-Type', 'text/json');

                } else {
                    return response($this->buildResponse('error', $user->errors()), 400)
                        ->header('Content-Type', 'text/json');
                }
            } else {
                return response($this->buildResponse('error', 'Необходимо указать email'), 400)
                    ->header('Content-Type', 'text/json');
            }
        } else {
            return response($this->buildResponse('error', 'Указаная при регистрации почта не подтверждена'), 403)
                ->header('Content-Type', 'text/json');
        }
    }

    //Смена email
    public function changeEmail(Request $request)
    {
        if ($request->has('email') && $request->has('code')) {
            $user = User::where('new_email', $request->email)->where('email_change_code', $request->code)->first();
            if ($user !== null) {
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
            return response($this->buildResponse('success', 'Данные успешно обновлены'), 200)
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
                return response($this->buildResponse('success', 'Пароль успешно изменён'), 200)
                    ->header('Content-Type', 'text/json');
            } else {
                return response($this->buildResponse('error', 'Текущие пароли не совпадают'), 400)
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

        //поддерживаются jpeg и png файлы
        $allowed_mime_type = [
            'image/jpeg',
            'image/png'
        ];



        if ($request->hasFile('avatar')) {
            if ($request->file('avatar')->isValid()){
                //размер файла не превышает двух мегабайт?
                if ($request->file('avatar')->getClientSize() < (1024 * 1024 * 2)) {
                    //файл имет допустимый тип?
                    if (in_array($request->file('avatar')->getMimeType(), $allowed_mime_type)) {
                        $user->saveAvatar($request->file('avatar'));
                        return response($this->buildResponse('success', 'Файл успешно загружен'), 200)
                            ->header('Content-Type', 'text/json');
                    } else {
                        return response($this->buildResponse('error', 'Данный тип файла не поддерживается'), 415)
                            ->header('Content-Type', 'text/json');
                    }
                } else {
                    return response($this->buildResponse('error', 'Размер файла не должен превышать двух мегабайт'), 413)
                        ->header('Content-Type', 'text/json');
                }
            } else {
                return response($this->buildResponse('error', 'Не удалось загрузить файл, попробуйте снова'), 500)
                    ->header('Content-Type', 'text/json');
            }
        } else {
            return response($this->buildResponse('error', 'Необходимо указать файл для загрузки'), 400)
                ->header('Content-Type', 'text/json');
        }
    }
}
