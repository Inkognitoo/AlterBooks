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
                'href' => route('user', ['id' => $user->id]),
            ];
        })
        ->toArray()
    ;

    $home = !empty(Auth::user()) ? route('user', ['id' => Auth::user()->id]) : null;

    return view('welcome', ['users' => $users, 'home' => $home]);
});

Auth::routes();

Route::get('user/id{id}', 'UserController@show')->name('user');