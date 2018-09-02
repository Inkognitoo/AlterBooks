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
    | User
    |--------------------------------------------------------------------------
    |
    | Здесь все маршруты касающиеся в первую очередь работы с пользователем
    |
    */
    Route::get('users', 'UserController@index')
        ->name('users')
    ;
    Route::get('user', 'UserController@createShow')
        ->name('user.create.show')
    ;
    Route::post('user', 'UserController@create')
        ->name('user.create')
    ;
    Route::get('user/{id}', 'UserController@show')
        ->name('user.show')
    ;
    Route::get('user/{id}/edit', 'UserController@edit')
        ->name('user.edit.show')
    ;
    Route::post('user/{id}/update', 'UserController@update')
        ->name('user.update')
    ;

    Route::get('books', 'BookController@index')
        ->name('books')
    ;
    Route::get('book/{id}', 'BookController@show')
        ->name('book.show')
    ;
    Route::get('book/{id}/edit', 'BookController@edit')
        ->name('book.edit.show')
    ;
    Route::post('book/{id}/update', 'BookController@update')
        ->name('book.update')
    ;

    Route::get('reviews', 'ReviewController@index')
        ->name('reviews')
    ;
    Route::get('review/{id}', 'ReviewController@show')
        ->name('review.show')
    ;
    Route::get('review/{id}/edit', 'ReviewController@edit')
        ->name('review.edit.show')
    ;
    Route::post('review/{id}/update', 'ReviewController@update')
        ->name('review.update')
    ;

    Route::get('genres', 'GenreController@index')
        ->name('genres')
    ;
    Route::get('genre/{id}', 'GenreController@show')
        ->name('genre.show')
    ;
    Route::get('genre/{id}/edit', 'GenreController@edit')
        ->name('genre.edit.show')
    ;
    Route::post('genre/{id}/update', 'GenreController@update')
        ->name('genre.update')
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

});
