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

Route::get('/', 'LandingPageController@index');

Auth::routes();

/*
|--------------------------------------------------------------------------
| User
|--------------------------------------------------------------------------
|
| Здесь все маршруты касающиеся в первую очередь работы с пользователем
|
*/
Route::get('user/id{id}', 'UserController@show')
    ->name('user.show')
;
Route::get('user/id{id}/edit', 'UserController@editShow')
    ->name('user.edit.show')
;
Route::post('user/id{id}/edit', 'UserController@edit')
    ->name('user.edit')
;

/*
|--------------------------------------------------------------------------
| Book
|--------------------------------------------------------------------------
|
| Здесь все маршруты касающиеся в первую очередь работы с книгами
|
*/
Route::get('book/id{id}', 'BookController@show')
    ->name('book.show')
;
Route::get('book/id{id}/edit', 'BookController@editShow')
    ->name('book.edit.show')
;
Route::post('book/id{id}/edit', 'BookController@edit')
    ->name('book.edit')
;
Route::get('book', 'BookController@createShow')
    ->name('book.create.show')
;
Route::post('book', 'BookController@create')
    ->name('book.create')
;
Route::get('book/id{id}/delete', 'BookController@delete')
    ->name('book.delete')
;
Route::get('books', 'BookController@showBooks')
    ->name('book.books-list')
;

// Reader
Route::get('book/id{id}/page/{page_number}', 'BookController@readPage')
    ->name('book.page.show')
;
Route::get('book/id{id}/page/{page_number}/edit', 'BookController@editPageShow')
    ->name('book.page.edit.show')
;
Route::post('book/id{id}/page/{page_number}/edit', 'BookController@editPage')
    ->name('book.page.edit')
;

/*
|--------------------------------------------------------------------------
| Review
|--------------------------------------------------------------------------
|
| Здесь все маршруты касающиеся в первую очередь работы с рецензиями
|
*/
Route::post('book/id{id}/review', 'ReviewController@create')
    ->name('review.create')
;
Route::get('book/id{book_id}/review/id{id}/delete', 'ReviewController@delete')
    ->name('review.delete')
;