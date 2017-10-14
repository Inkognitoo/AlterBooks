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

    $books = \App\Book::all()
        ->map(function($book) {
            return [
                'title' => $book->title,
                'href' => route('book_show', ['id' => $book->id]),
            ];
        })
        ->toArray()
    ;

    return view('welcome', [
        'users' => $users,
        'books' => $books,
    ]);
});

Auth::routes();

Route::get('user/id{id}', 'UserController@show')
    ->name('user_show')
    ->middleware('checkUserExist')
;
Route::get('user/id{id}/edit', 'UserController@editShow')
    ->name('user_edit_show')
    ->middleware('checkUserExist')
    ->middleware('checkAuth')
    ->middleware('checkUserGranted')
;
Route::post('user/id{id}/edit', 'UserController@edit')
    ->name('user_edit')
    ->middleware('checkUserExist')
    ->middleware('checkAuth')
    ->middleware('checkUserGranted')
;

Route::get('book/id{id}', 'BookController@show')
    ->name('book_show')
    ->middleware('checkBookExist')
;
Route::get('book/id{id}/edit', 'BookController@editShow')
    ->name('book_edit_show')
    ->middleware('checkBookExist')
    ->middleware('checkAuth')
    ->middleware('checkUserBookGranted')
;
Route::post('book/id{id}/edit', 'BookController@edit')
    ->name('book_edit')
    ->middleware('auth')
    ->middleware('checkBookExist')
    ->middleware('checkAuth')
    ->middleware('checkUserBookGranted')
;
Route::get('book', 'BookController@createShow')
    ->name('book_create_show')
    ->middleware('checkAuth')
;
Route::post('book', 'BookController@create')
    ->name('book_create')
    ->middleware('checkAuth')
;