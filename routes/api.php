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

Route::post('login', 'Api\AuthController@login')->name('api_login');
Route::post('register', 'Api\AuthController@register')->name('api_register');;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function () {
    // auth
    Route::post('auth', 'Api\AuthController@user');
    Route::post('logout','Api\AuthController@logout')->name('api_logout');

    // users
    Route::get('users', 'UserController@index');
    Route::get('user/{id}', 'UserController@show');
    Route::post('users', 'UserController@store');
    Route::put('users', 'UserController@store');
    Route::delete('users/{id}', 'UserController@destroy');

    // people (tourists)
    Route::get('people', 'PeopleController@index');
    Route::get('people/{id}', 'PeopleController@show');
    Route::post('people', 'PeopleController@store');
    Route::put('people', 'PeopleController@store');
    Route::delete('people/{id}', 'PeopleController@destroy');

    // places (ex: museums)
    Route::get('place', 'PlaceController@index');
    Route::get('place/{id}', 'PlaceController@show');
    Route::post('place', 'PlaceController@store');
    Route::put('place', 'PlaceController@store');
    Route::delete('place/{id}', 'PlaceController@destroy');
});
