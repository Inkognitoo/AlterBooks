<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'LoginController@showLoginForm')->name('login.show');

Route::post('/login', 'LoginController@login')->name('login');

Route::group(['middleware' => 'auth'], function() {
    Route::get('/logout', 'LoginController@logout')
        ->name('logout')
    ;
    Route::get('/dashboard', 'DashboardController@index')
        ->name('dashboard')
    ;

    /*
    |--------------------------------------------------------------------------
    | Additional
    |--------------------------------------------------------------------------
    |
    | Здесь различные, дополнительные маршруты
    |
    */

    //маршрут для просмотра логов
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')
        ->name('logs')
    ;

    Route::get('users', 'UserController@index')
        ->name('users')
    ;

    Route::get('user/{id}', 'UserController@show')
        ->name('user.show')
    ;

    Route::get('books', 'BookController@index')
        ->name('books')
    ;

    Route::get('reviews', 'ReviewController@index')
        ->name('reviews')
    ;

    Route::get('genres', 'GenreController@index')
        ->name('genres')
    ;
});
