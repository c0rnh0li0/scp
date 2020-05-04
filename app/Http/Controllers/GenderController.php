<?php

namespace App\Http\Controllers;

use App\Gender;
use App\Http\Resources\Gender as GenderResource;
use Illuminate\Http\Request;

class GenderController extends Controller
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

        $genders = Gender::where('name', 'like', "%$q%")->orderBy($sortBy, $dir)->paginate($itemsPerPage);

        return GenderResource::collection($genders);
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
            'name' => 'required|max:191'
        ];

        $this->validate($request, $rules);

        $gender = $request->input('id') <= 0 ? new Gender() : Gender::findOrFail($request->input('id'));
        $gender->name = $request->input('name');

        if ($gender->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Gender successfully saved!',
                'item' => new GenderResource($gender)
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error saving gender!'
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Gender  $gender
     * @return \Illuminate\Http\Response
     */
    public function show(Gender $gender)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Gender  $gender
     * @return \Illuminate\Http\Response
     */
    public function edit(Gender $gender)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Gender  $gender
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gender $gender)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Gender  $gender
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $gender = Gender::findOrFail($id);

        if ($gender->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Gender successfully deleted!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting gender!'
            ], 201);
        }
    }
}
