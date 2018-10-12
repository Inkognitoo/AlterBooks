<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function() {

    /*
    |--------------------------------------------------------------------------
    | User
    |--------------------------------------------------------------------------
    |
    | Здесь все маршруты касающиеся в первую очередь работы с пользователями
    |
    */
    Route::post('/users', 'UserController@index')
        ->name('api.users')
    ;

    Route::post('/books', 'BookController@index')
        ->name('api.books')
    ;

    Route::post('/reviews', 'ReviewController@index')
        ->name('api.reviews')
    ;

    Route::post('/genres', 'GenreController@index')
        ->name('api.genres')
    ;

});
