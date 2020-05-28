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

//URL::forceScheme('https');

Route::post('login', 'Api\AuthController@login')->name('api_login');
Route::post('register', 'Api\AuthController@register')->name('api_register');;
Route::get('languages', 'LanguageController@index');

// settings data
Route::middleware('api')->group(function () {
    Route::get('settings', 'SettingsController@index');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function () {
    // auth
    Route::post('auth', 'Api\AuthController@user');
    Route::post('logout', 'Api\AuthController@logout')->name('api_logout');
    Route::post('password', 'Api\AuthController@password');

    // charts data
    Route::get('monthlytickets/{id?}', 'ChartsController@monthlytickets');
    Route::get('yearlytickets/{id?}', 'ChartsController@yearlytickets');
    Route::get('yearlyvisitors/{id?}', 'ChartsController@yearlyvisitors');
    Route::get('dailyticketsdata/{id?}', 'ChartsController@dailyticketsdata');
    Route::get('dailyvisitorsdata', 'ChartsController@dailyvisitorsdata');
    Route::get('dailytouristsdata', 'ChartsController@dailytouristsdata');
    Route::get('dailyvisitsdata/{id?}', 'ChartsController@dailyvisitsdata');
    Route::get('mostvisitedplaces', 'ChartsController@mostvisitedplaces');

    // users
    Route::get('users', 'UserController@index');
    Route::post('users', 'UserController@store');
    Route::put('users', 'UserController@store');
    Route::delete('users/{id}', 'UserController@destroy');
    Route::get('profile/{id}', 'UserController@profile');

    // people (tourists)
    Route::get('people', 'PeopleController@index');
    Route::get('people/{id}', 'PeopleController@show');
    Route::post('people', 'PeopleController@store');
    Route::put('people', 'PeopleController@store');
    Route::post('people/delete/{id}', 'PeopleController@delete');

    // places (ex: museums)
    Route::get('place', 'PlaceController@index');
    Route::get('place/find', 'PlaceController@find');
    Route::get('place/{id}', 'PlaceController@show');
    Route::post('place', 'PlaceController@store');
    Route::put('place', 'PlaceController@store');
    Route::post('place/delete/{id}', 'PlaceController@delete');

    // user details
    Route::post('userdetails/save/{id}', 'UserDetailController@save');
    Route::get('userdetails/{id}', 'UserDetailController@get');

    // offers
    Route::get('offers', 'OfferController@index');
    Route::get('offers/list/{id?dailyticketsdata}', 'OfferController@list');
    Route::get('offers/get/{fromDashboard?}', 'OfferController@get');
    Route::post('offers/save/{id}', 'OfferController@save');
    Route::post('offers/delete/{id}', 'OfferController@delete');

    // tickets
    Route::get('tickets', 'TicketController@index');
    Route::get('tickets/list', 'TicketController@list');
    Route::post('tickets/buy', 'TicketController@buy');
    Route::post('tickets/check', 'TicketController@check');
    Route::post('tickets/use', 'TicketController@use');
    Route::get('tickets/qr/{user}/{offer}/{ticket}/{amount}', 'TicketController@qr');

    // settings
    Route::post('settings/save', 'SettingsController@save');

    // lookup data
    Route::get('lookups', 'HomeController@lookups');

    Route::get('categories', 'CategoryController@index');
    Route::post('categories/save', 'CategoryController@save');
    Route::post('categories/delete/{id}', 'CategoryController@delete');

    Route::get('countries', 'CountryController@index');
    Route::post('countries/save', 'CountryController@save');
    Route::post('countries/delete/{id}', 'CountryController@delete');

    Route::get('cities', 'CityController@index');
    Route::post('cities/save', 'CityController@save');
    Route::post('cities/delete/{id}', 'CityController@delete');

    Route::get('genders', 'GenderController@index');
    Route::post('genders/save', 'GenderController@save');
    Route::post('genders/delete/{id}', 'GenderController@delete');

    Route::post('languages/save', 'LanguageController@save');
    Route::post('languages/delete/{id}', 'LanguageController@delete');

    Route::get('usertypes', 'UserTypeController@index');
    Route::post('usertypes/save', 'UserTypeController@save');
    Route::post('usertypes/delete/{id}', 'UserTypeController@delete');

    Route::get('valutes', 'ValuteController@index');
    Route::post('valutes/save', 'ValuteController@save');
    Route::post('valutes/delete/{id}', 'ValuteController@delete');

    Route::get('contractlengths', 'ContractLengthController@index');
    Route::post('contractlengths/save', 'ContractLengthController@save');
    Route::post('contractlengths/delete/{id}', 'ContractLengthController@delete');

    Route::get('contracts', 'ContractController@index');
    Route::post('contracts/save', 'ContractController@save');
});
