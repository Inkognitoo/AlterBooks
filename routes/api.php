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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
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
    Route::post('/book/{book_id}/review/id{id}/estimate/plus', 'ReviewEstimateController@plus')
        ->name('api.review.estimate.plus')
    ;
    Route::post('/book/{book_id}/review/id{id}/estimate/minus', 'ReviewEstimateController@minus')
        ->name('api.review.estimate.minus')
    ;
});
