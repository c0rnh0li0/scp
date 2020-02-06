<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserDetails as UserDetailResource;
use App\User;
use App\UserDetail;
use App\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request as FacadesRequest;

class PlaceController extends UserDetailController
{
    public function index()
    {
        $sort = FacadesRequest::get('sort', 'created_at');
        $dir = FacadesRequest::get('direction', 'asc');
        $search = FacadesRequest::get('q', '');

        if ($sort == '')
            $sort = 'created_at';

        if ($search != '') {
            $users = UserDetail::where('is_company', 1)
                ->where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('description', 'LIKE', '%' . $search . '%')
                ->paginate(10);
        } else {
            $users = UserDetail::where('is_company', 1)->orderBy($sort, $dir)->paginate(10);
        }

        return UserDetailResource::collection($users);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*$user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $is_company = array_key_exists('is_company', $data) && $data['is_company'] == 'on' ? 1 : 0;
        UserDetail::create([
            'user_id' => $user->id,
            'user_type_id' => $is_company == 1 ? UserType::COMPANY_USER_TYPE : UserType::TOURIST_USER_TYPE,
            'is_company' => $is_company
        ]);

        return $user;*/
    }

    /**
     * Display the specified resource.
     *
     * @param \App\UserDetail $userDetail
     * @return \Illuminate\Http\Response
     */
    public function show(UserDetail $userDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\UserDetail $userDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(UserDetail $userDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\UserDetail $userDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserDetail $userDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\UserDetail $userDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserDetail $userDetail)
    {
        //
    }
}
