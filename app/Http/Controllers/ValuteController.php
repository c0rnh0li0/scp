<?php

namespace App\Http\Controllers;

use App\Valute;
use App\Http\Resources\Valute as ValuteResource;
use Illuminate\Http\Request;

class ValuteController extends Controller
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

        $valutes = Valute::where('name', 'like', "%$q%")->orderBy($sortBy, $dir)->paginate($itemsPerPage);

        return ValuteResource::collection($valutes);
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
            'sign' => 'required'
        ];

        $this->validate($request, $rules);

        $valute = $request->input('id') <= 0 ? new Valute() : Valute::findOrFail($request->input('id'));
        $valute->name = $request->input('name');
        $valute->sign = $request->input('sign');

        if ($valute->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Valute successfully saved!',
                'item' => new ValuteResource($valute)
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error saving valute!'
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Valute  $valute
     * @return \Illuminate\Http\Response
     */
    public function show(Valute $valute)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Valute  $valute
     * @return \Illuminate\Http\Response
     */
    public function edit(Valute $valute)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Valute  $valute
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Valute $valute)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Valute  $valute
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $valute = Valute::findOrFail($id);

        if ($valute->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Valute successfully deleted!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting valute!'
            ], 201);
        }
    }
}
