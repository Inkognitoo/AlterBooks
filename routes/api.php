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

Route::group(['prefix' => 'v1', 'namespace' => 'Api'], function(){

    Route::post('/login', 'UserController@login')
        ->name('api.user.login')
    ;

    Route::get('/user', 'UserController@index')
        ->name('api.user.info')
    ;

    Route::get('/books', 'BookController@index')
        ->name('api.book.list')
    ;
    Route::get('/books/tips', 'BookController@tips')
        ->name('api.book.tips')
    ;

    Route::get('/genres', 'GenreController@index')
        ->name('api.genre.list')
    ;
});

Route::group(['prefix' => 'v1'], function(){

    /*
    |--------------------------------------------------------------------------
    | Registration
    |--------------------------------------------------------------------------
    |
    | Здесь все маршруты касающиеся регистрации
    |
    */
    Route::post('/registration/validate', 'Api\RegistrationController@validator')
        ->name('api.registration.validate')
    ;
});

Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function(){

    /*
    |--------------------------------------------------------------------------
    | Book
    |--------------------------------------------------------------------------
    |
    | Здесь все маршруты касающиеся в первую очередь работы с книгами
    |
    */
    Route::post('/library/book/{id}', 'LibraryBookController@create')
        ->name('api.library.add')
    ;
    Route::delete('/library/book/{id}', 'LibraryBookController@destroy')
        ->name('api.library.delete')
    ;

    /*
    |--------------------------------------------------------------------------
    | Review
    |--------------------------------------------------------------------------
    |
    | Здесь все маршруты касающиеся в первую очередь работы с рецензиями
    |
    */
    Route::post('/book/{book_id}/review', 'Api\ReviewController@create')
        ->name('api.review.create')
    ;
    Route::post('/book/{book_id}/review/{id}/estimate/plus', 'ReviewEstimateController@plus')
        ->name('api.review.estimate.plus')
    ;
    Route::post('/book/{book_id}/review/{id}/estimate/minus', 'ReviewEstimateController@minus')
        ->name('api.review.estimate.minus')
    ;
    Route::delete('/book/{book_id}/review/{id}/delete', 'Api\ReviewController@delete')
        ->name('api.review.delete')
    ;
    Route::put('/review/{book_id}/restore', 'Api\ReviewController@restore')
        ->name('api.review.restore')
    ;
    Route::put('/book/{book_id}/review/{id}/edit', 'Api\ReviewController@edit')
        ->name('api.review.edit')
    ;

    /*
    |--------------------------------------------------------------------------
    | Blog
    |--------------------------------------------------------------------------
    |
    | Здесь все маршруты, касающиеся в первую очередь, работы с блогом
    |
    */

    Route::post('blog/article','Api\ArticleController@create')
        ->name('api.blog.create');

});
