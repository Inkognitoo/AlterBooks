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

Route::group(['prefix' => 'api/v1'], function()
{
    Route::group(['middleware' => 'reg.validate'], function() {
        Route::post('registration', [
            'uses' => 'UsersController@registration'
        ]);
    });

    Route::post('login', [
        'uses' => 'UsersController@login'
    ]);

});

// Логи приложения. Только для локала
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

// Для single page. Любой не зарегистрированый маршрут отправляет к index
Route::get('{path}', function () {
    return view('index');
})
    ->where('path', '.*?');

//// Роуты запроса ссылки для сброса пароля
//Route::get('password/email', 'Auth\PasswordController@getEmail');
//Route::post('password/email', 'Auth\PasswordController@postEmail');
//
//// Роуты сброса пароля
//Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
//Route::post('password/reset', 'Auth\PasswordController@postReset');
