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

Route::get('/', function () {
    $users = \App\User::all()
        ->map(function($user) {
            return [
                'nickname' => $user->nickname,
                'href' => route('user_show', ['id' => $user->id]),
            ];
        })
        ->toArray()
    ;

    return view('welcome', ['users' => $users]);
});

Auth::routes();

Route::get('user/id{id}', 'UserController@show')->name('user_show');

Route::get('user/id{id}/edit', 'UserController@editShow')->name('user_edit_show');

Route::post('user/id{id}/edit', 'UserController@edit')->name('user_edit');

