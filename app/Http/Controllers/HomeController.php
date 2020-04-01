<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\City;
use App\Country;
use App\Gender;
use App\Valute;

use App\Http\Resources\Valute as ValuteResource;
use App\Http\Resources\Country as CountryResource;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\City as CityResource;
use App\Http\Resources\Gender as GenderResource;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('api:auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('/');
    }

    public function admin()
    {
        return view('admin');
    }

    public function place()
    {
        return view('place');
    }

    public function home()
    {
        return view('home');
    }

    public function lookups() {
        $cities_raw = City::all();
        $categories_raw = Category::where('parent_id', null)->get();
        $genders_raw = Gender::all();
        $valutes_raw = Valute::all();
        $countries_raw = Country::all();

        return response()->json([
            'cities' => CityResource::collection($cities_raw),
            'categories' => CategoryResource::collection($categories_raw),
            'genders' => GenderResource::collection($genders_raw),
            'valutes' => ValuteResource::collection($valutes_raw),
            'countries' => CountryResource::collection($countries_raw)
        ]);
    }
}
