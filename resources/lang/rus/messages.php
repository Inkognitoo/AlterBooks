<?php

return [
    /*
     * UserController
     */

    'registration_success'  => 'Регистрация почти завершена. Вам необходимо подтвердить email, указанный при регистрации, перейдя по ссылке в письме',

    'send_email_verify_success' => 'Новый запрос на подтверждение почты был успешно отправлен',
    'send_email_verify_error'   => 'Ваш email уже прошёл верификацию',

    'auth_success'                      => 'Пользователь успешно авторизован',
    'auth_bad_password'                 => 'Неверный пароль',
    'auth_user_not_found'               => 'Пользовтеля не существует',
    'auth_not_enter_login_and_password' => 'Необходимо указать логин и пароль',

    'unauth_success' => 'Сессия пользователя была успешно прекращена',

    'reset_password_request_success'         => 'Код восстановления был выслан на указанный email',
    'reset_password_request_email_not_found' => 'Указаного email не существует в базе либо email не был подтвержён',
    'reset_password_request_email_not_enter' => 'Необходимо указать email',

    'change_email_request_success'    => 'Запрос на смену email успешно отправлен',
    'change_email_request_not_verify' => 'Указаная при регистрации почта не подтверждена',

    'filling_profile_success' => 'Данные успешно обновлены',

    'change_password_success'   => 'Пароль успешно изменён',
    'change_password_not_match' => 'Текущие пароли не совпадают',

    'upload_avatar_success' => 'Файл успешно загружен',

    /*
     * SocialController
     */

    'social_auth_provider_not_found' => 'Данный провайдер не поддерживается',

    'social_auth_callback_registration_success' => 'Регистрация почти завершена. Вам необходимо подтвердить email, указанный при регистрации, перейдя по ссылке в письме.',
    'social_auth_callback_auth_success'         => 'Пользователь успешно авторизирован',
    'social_auth_callback_not_giving_date'      => 'Не удалось получить данные из социальной сети',
    'social_auth_callback_not_process_date'     => 'Не удалось обработать данные из социальной сети',
    'social_auth_callback_email_in_use'         => 'Указанный email уже занят',

    'enter_email_success'          => 'Регистрация почти завершена. Вам необходимо подтвердить email, указанный при регистрации, перейдя по ссылке в письме.',
    'enter_email_not_process_date' => 'Не удалось обработать данные из социальной сети',
    'enter_email_forbidden'        => 'В доступе отказано',
];