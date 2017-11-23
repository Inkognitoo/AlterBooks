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

Route::group(['prefix' => 'v1'], function(){
    Route::post('library/id{id}', 'LibraryBookController@create')
        ->name('api.library.add')
    ;
    Route::delete('library/id{id}', 'LibraryBookController@destroy')
        ->name('api.library.delete')
    ;
});
