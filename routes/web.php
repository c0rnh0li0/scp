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

/*Route::get('/', function () {
    return view('welcome');
})->name('index');*/
//URL::forceScheme('https');

Route::get('/mostvisitedplaces', 'ChartsController@mostvisitedplaces');

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');

Route::get('/', 'HomeController@home')->name('index');
Route::get('/admin/{slug?}', 'HomeController@home')->name('admin');
Route::get('/admin/{slug?}/{slug1?}', 'HomeController@home')->name('admin');
Route::get('/place/{slug?}', 'HomeController@home')->name('place');
Route::get('/home/{slug?}', 'HomeController@home')->name('home');

Route::get('/about', 'HomeController@home')->name('about');
Route::get('/offline', 'HomeController@home')->name('offline');
Route::get('/error', 'HomeController@home')->name('error');


