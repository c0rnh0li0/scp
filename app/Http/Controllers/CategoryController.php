<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Resources\Category as CategoryResource;

class CategoryController extends Controller
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

        $categories = Category::where('name', 'like', "%$q%")->orderBy($sortBy, $dir)->paginate($itemsPerPage);

        return CategoryResource::collection($categories);
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

        $category = $request->input('id') <= 0 ? new Category() : Category::findOrFail($request->input('id'));
        $category->name = $request->input('name');
        if ($request->input('parent_id') > 0)
            $category->parent_id = $request->input('parent_id');

        if ($category->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Offer successfully saved!',
                'item' => new CategoryResource($category)
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error saving offer!'
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
        $category = Category::findOrFail($id);

        if ($category->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Category successfully deleted!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting category!'
            ], 201);
        }
    }
}
