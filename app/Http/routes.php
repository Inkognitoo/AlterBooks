<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/
Route::group(['middleware' => ['web']], function () {
    //Social Login
    Route::get('social/oauth/{provider?}', 'SocialController@socialAuth');
    Route::get('social/oauth/redirect/{provider?}', 'SocialController@socialAuthCallback');
});

Route::group(['prefix' => 'api'], function() {
    Route::group(['prefix' => 'v1'], function() {
        Route::post('user', [
            'as' => 'registration', 'uses' => 'UserController@registration'
        ]);
        //Ввод email, когда он отсутствует или неуникален для Oauth
        Route::post('user/email', [
            'middleware' => 'web',
            'as' => 'enter email', 'uses' => 'SocialController@enterEmail'
        ]);
    });
});

//Route::group(['middleware' => ['web']], function () {
//    //
//});
//
//Route::group(['middleware' => 'web'], function () {
//    Route::auth();
//
//    Route::get('/home', 'HomeController@index');
//});
