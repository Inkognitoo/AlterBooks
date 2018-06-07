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

Route::get('/dashboard', function (){
    return auth()->user();
})->name('dashboard');

Route::get('/', 'LoginController@showLoginForm')->name('login.form');

Route::post('/login', 'LoginController@login')->name('login');

Route::get('/logout', 'LoginController@logout')->name('logout');
