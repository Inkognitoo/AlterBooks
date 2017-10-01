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
                'name' => $user->name,
                'href' => route('user', ['id' => $user->id]),
            ];
        })
        ->toArray()
    ;
    return view('welcome', ['users' => $users]);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('user/id{id}', 'UserController@show')->name('user');