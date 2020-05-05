<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContractLength as ContractLengthResource;
use App\Http\Resources\ContractLengthList as ContractLengthResourceList;
use App\ContractLength;
use Illuminate\Http\Request;

class ContractLengthController extends Controller
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

        $contractLengths = ContractLength::where('name', 'like', "%$q%")->orderBy($sortBy, $dir)->paginate($itemsPerPage);

        return ContractLengthResourceList::collection($contractLengths);
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
            'duration' => 'required|numeric',
            'price' => 'required|numeric',
            'valute_id' => 'required|numeric',
        ];

        $this->validate($request, $rules);

        $contractLength = $request->input('id') <= 0 ? new ContractLength() : ContractLength::findOrFail($request->input('id'));
        $contractLength->name = $request->input('name');
        $contractLength->duration = $request->input('duration');
        $contractLength->price = $request->input('price');
        $contractLength->valute_id = $request->input('valute_id');

        if ($contractLength->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Contract length successfully saved!',
                'item' => new ContractLengthResource($contractLength)
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error saving contract length!'
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
        $contractLength = ContractLength::findOrFail($id);

        if ($contractLength->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Contract length successfully deleted!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting contract length!'
            ], 201);
        }
    }
}
