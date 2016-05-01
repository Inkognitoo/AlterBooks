<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//социальная авторизация
Route::get('social/oauth/{provider?}', 'SocialController@socialAuth');
Route::get('social/oauth/redirect/{provider?}', 'SocialController@socialAuthCallback');

Route::group(['prefix' => 'api'], function() {
    Route::group(['prefix' => 'v1'], function() {
        /*********************
         * Открытые методы API
         *********************/
        //обычная регистрация пользователя
        Route::post('user', [
            'as' => 'registration', 'uses' => 'UserController@registration'
        ]);

        //авторизация пользователя
        Route::post('user/session', [
            'as' => 'authenticate', 'uses' => 'UserController@auth'
        ]);

        //запрос на сброс пароля
        Route::post('user/password/reset', [
            'as' => 'password reset request', 'uses' => 'UserController@resetPasswordRequest'
        ]);

        /**********************
         * Защищёные методы API
         **********************/
        Route::group(['middleware' => 'auth'], function() {
            //повторный запрос верификации email пользователя
            Route::post('user/email/verify/request', [
                'as' => 'email verify request', 'uses' => 'UserController@sendEmailVerify'
            ]);

            //деавторизация пользователя
            Route::delete('user/session', [
                'as' => 'authenticate', 'uses' => 'UserController@unauth'
            ]);

            //запрос на смену email
            Route::put('user/email', [
                'as' => 'change email request', 'uses' => 'UserController@changeEmailRequest'
            ]);

            //заполнение стандартных значений профиля
            Route::put('user/profile', [
                'as' => 'filling profile', 'uses' => 'UserController@fillingProfile'
            ]);

            //смена пароля
            Route::put('user/profile/password', [
                'as' => 'change password', 'uses' => 'UserController@changePassword'
            ]);
        });
    });
});

/************************
 * Открытые общие методы
 ************************/

//верификация email
Route::get('user/email/verify', [
    'as' => 'email verify', 'uses' => 'UserController@emailVerify'
]);

//сброс пароля
Route::get('user/password/reset', [
    'as' => 'reset password', 'uses' => 'UserController@resetPassword'
]);

//Установка нового email
Route::get('user/email/change', [
    'as' => 'change email', 'uses' => 'UserController@changeEmail'
]);
