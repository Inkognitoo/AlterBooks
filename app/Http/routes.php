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
    // Регистрация пользователя
    Route::group(['middleware' => 'reg.validate'], function() {
        Route::post('user', [
            'uses' => 'UsersController@registration'
        ]);
    });

    // Аутентификация пользователя
    Route::post('user/session', [
        'uses' => 'UsersController@login'
    ]);

    // Активация пользователя
    Route::get('activate/{id}/{activation_code}', [
        'uses' => 'UsersController@activate'
    ]);

    // Получение сериализованного объекта книги
    Route::get('book/{id}', [
       'uses' => 'BookController@index'
    ])->where('id', '[0-9]+');

    Route::group(['middleware' => 'auth'], function() {
        Route::delete('user/session', [
            'uses' => 'UsersController@logout'
        ]);

        // Создание новой книги
        Route::post('book', [
            'uses' => 'BookController@index'
        ]);

        // Редактирование книги
        Route::put('book/{id}', [
            'uses' => 'BookController@index'
        ])->where('id', '[0-9]+');
    });



});
