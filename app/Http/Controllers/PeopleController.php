<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserDetails as UserDetailResource;
use App\UserDetail;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeopleController extends UserDetailController
{
    /**
     * People table data.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function index(Request $request)
    {
        $itemsPerPage = $request->input('itemsPerPage');
        $page = $request->input('page');

        $sortBy = $request->input('sortBy');
        $sortBy = str_replace('user.', 'users.', $sortBy);

        $dir = $request->input('dir');
        $q = $request->input('q');

        if ($sortBy == '')
            $sortBy = 'user_details.created_at';

        $users = UserDetail::join('users', 'users.id', '=', 'user_details.user_id')
                ->where('user_details.is_company', 0)
                ->orderBy($sortBy, $dir)
                ->whereHas('user', function ($query) use ($q) {
                    if ($q && $q != '') {
                        $query->where('name', 'like', "%$q%")
                            ->orWhere('email', 'like', "%$q%");
                    }
                })
                ->select('user_details.*')
                ->paginate($itemsPerPage);

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
     * @param  \Illuminate\Http\Request  $request
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
     * @param  \App\UserDetail  $userDetail
     * @return \Illuminate\Http\Response
     */
    public function show(UserDetail $userDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserDetail  $userDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(UserDetail $userDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserDetail  $userDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserDetail $userDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserDetail  $userDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserDetail $userDetail)
    {
        //
    }
}
