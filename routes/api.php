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
    Route::post('/book/{book_id}/review/crete', 'Api\ReviewController@create')
        ->name('api.review.create')
    ;
    Route::post('/book/{book_id}/review/id{id}/estimate/plus', 'ReviewEstimateController@plus')
        ->name('api.review.estimate.plus')
    ;
    Route::post('/book/{book_id}/review/id{id}/estimate/minus', 'ReviewEstimateController@minus')
        ->name('api.review.estimate.minus')
    ;
    Route::delete('/review/id{id}/delete', 'Api\ReviewController@delete')
        ->name('api.review.delete')
    ;
    Route::put('/review/{book_id}/restore', 'Api\ReviewController@restore')
        ->name('api.review.restore')
    ;
    Route::put('/book/{book_id}/review/id{id}/edit', 'Api\ReviewController@edit')
        ->name('api.review.edit')
    ;
});
