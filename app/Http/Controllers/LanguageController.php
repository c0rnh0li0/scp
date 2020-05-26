<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Language;
use App\Http\Resources\Language as LanguageResource;

class LanguageController extends Controller
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

        $dir = $request->input('dir', 'asc');

        if ($sortBy == '')
            $sortBy = 'created_at';

        $q = $request->input('q');

        $languages = Language::where('name', 'like', "%$q%")->orderBy($sortBy, $dir)->paginate($itemsPerPage);

        return LanguageResource::collection($languages);
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
            'locale' => 'required'
        ];

        $this->validate($request, $rules);

        $language = $request->input('id') <= 0 ? new Language() : Language::findOrFail($request->input('id'));
        $language->name = $request->input('name');
        $language->locale = $request->input('locale');

        if ($language->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Language successfully saved!',
                'item' => new LanguageResource($language)
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error saving language!'
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $language = Language::findOrFail($id);

        if ($language->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Language successfully deleted!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting language!'
            ], 201);
        }
    }
}
