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

// users
Route::get('users', 'UserController@index');
Route::get('user/{id}', 'UserController@show');
Route::post('users', 'UserController@store');
Route::put('users', 'UserController@store');
Route::delete('users/{id}', 'UserController@destroy');

// user details
Route::get('people', 'PeopleController@index');
Route::get('people/{id}', 'PeopleController@show');
Route::post('people', 'PeopleController@store');
Route::put('people', 'PeopleController@store');
Route::delete('people/{id}', 'PeopleController@destroy');

Route::get('place', 'PlaceController@index');
Route::get('place/{id}', 'PlaceController@show');
Route::post('place', 'PlaceController@store');
Route::put('place', 'PlaceController@store');
Route::delete('place/{id}', 'PlaceController@destroy');