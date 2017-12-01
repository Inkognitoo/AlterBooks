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
    Route::post('library/book/id{id}', 'LibraryBookController@create')
        ->name('api.library.add')
    ;
    Route::delete('library/book/id{id}', 'LibraryBookController@destroy')
        ->name('api.library.delete')
    ;
});
