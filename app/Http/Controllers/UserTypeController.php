<?php

namespace App\Http\Controllers;

use App\UserType;
use App\Http\Resources\UserType as UserTypeResource;
use Illuminate\Http\Request;

class UserTypeController extends Controller
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

        $userTypes = UserType::where('name', 'like', "%$q%")->orderBy($sortBy, $dir)->paginate($itemsPerPage);

        return UserTypeResource::collection($userTypes);
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

        $userType = $request->input('id') <= 0 ? new UserType() : UserType::findOrFail($request->input('id'));
        $userType->name = $request->input('name');

        if ($userType->save()) {
            return response()->json([
                'success' => true,
                'message' => 'User type successfully saved!',
                'item' => new UserTypeResource($userType)
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error saving user type!'
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserType  $userType
     * @return \Illuminate\Http\Response
     */
    public function show(UserType $userType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserType  $userType
     * @return \Illuminate\Http\Response
     */
    public function edit(UserType $userType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserType  $userType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserType $userType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserType  $userType
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $userType = UserType::findOrFail($id);

        if ($userType->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'User type successfully deleted!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting user type!'
            ], 201);
        }
    }
}
