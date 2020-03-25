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

Route::get('/', function () {
    return view('welcome');
})->name('index');

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
//Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');

Route::get('/admin/{slug?}', 'HomeController@admin')->name('admin');
Route::get('/place/{slug?}', 'HomeController@place')->name('place');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/error', 'HomeController@error')->name('error');
/*
| POST     | password/confirm                        |                                   | App\Http\Controllers\Auth\ConfirmPasswordController@confirm               | web,auth           |
|        | GET|HEAD | password/confirm                        | password.confirm                  | App\Http\Controllers\Auth\ConfirmPasswordController@showConfirmForm       | web,auth           |
|        | POST     | password/email                          | password.email                    | App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail     | web                |
|        | GET|HEAD | password/reset                          | password.request                  | App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm    | web                |
|        | POST     | password/reset                          | password.update                   | App\Http\Controllers\Auth\ResetPasswordController@reset                   | web                |
|        | GET|HEAD | password/reset/{token}                  | password.reset                    | App\Http\Controllers\Auth\ResetPasswordController@showResetForm           | web                |
|        | GET|HEAD | place                                   | place                             | App\Http\Controllers\HomeController@place                                 | web,auth:api       |
|        | GET|HEAD | register                                | register                          | App\Http\Controllers\Auth\RegisterController@showRegistrationForm         | web,guest          |
|        | POST     | register                                |                                   | App\Http\Controllers\Auth\RegisterController@register                     | web,guest          |
|        | POST     | login                                   |                                   | App\Http\Controllers\Auth\LoginController@login                           | web,guest          |
|        | GET|HEAD | login                                   | login                             | App\Http\Controllers\Auth\LoginController@showLoginForm                   | web,guest          |
|        | POST     | logout                                  | logout                            | App\Http\Controllers\Auth\LoginController@logout                          | web                |
*/

//Auth::routes();
