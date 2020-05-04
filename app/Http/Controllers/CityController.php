<?php

namespace App\Http\Controllers;

use App\City;
use App\Http\Resources\City as CityResource;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itemsPerPage = $request->input('itemsPerPage');
        if ($itemsPerPage < 0)
            $itemsPerPage = 10000;

        $page = $request->input('page');

        $sortBy = $request->input('sortBy');
        $sortBy = str_replace('user.', 'users.', $sortBy);

        $dir = $request->input('dir');

        if ($sortBy == '')
            $sortBy = 'created_at';

        $q = $request->input('q');

        $cities = City::where('name', 'like', "%$q%")->orderBy($sortBy, $dir)->paginate($itemsPerPage);

        return CityResource::collection($cities);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $rules = [
            'name' => 'required|max:191',
            'country_id' => 'required'
        ];

        $this->validate($request, $rules);

        $city = $request->input('id') <= 0 ? new City() : City::findOrFail($request->input('id'));
        $city->name = $request->input('name');
        $city->country_id = $request->input('country_id');

        if ($city->save()) {
            return response()->json([
                'success' => true,
                'message' => 'City successfully saved!',
                'item' => new CityResource($city)
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error saving city!'
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $city = City::findOrFail($id);

        if ($city->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'City successfully deleted!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting city!'
            ], 201);
        }
    }
}
