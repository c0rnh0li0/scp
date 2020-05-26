<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserDetails as UserDetailResource;
use App\Http\Resources\UserDetails;
use App\User;
use App\UserDetail;
use App\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\DB;

class PlaceController extends UserDetailController
{
    /**
     * Places table data.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return App\Http\Resources\UserDetails
     */
    public function index(Request $request)
    {
        $itemsPerPage = $request->input('itemsPerPage', 1000);
        $page = $request->input('page');

        $sortBy = $request->input('sortBy');
        $sortBy = str_replace('user.', 'users.', $sortBy);

        $dir = $request->input('dir', 'asc');
        $q = $request->input('q');

        if ($sortBy == '')
            $sortBy = 'user_details.created_at';

        $users = UserDetail::join('users', 'users.id', '=', 'user_details.user_id')
            ->where('user_details.is_company', 1)
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
     * Places search data.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return App\Http\Resources\UserDetails
     */
    public function find(Request $request)
    {
        $q = $request->input('q');

        $users = UserDetail::join('users', 'users.id', '=', 'user_details.user_id')
            ->where('user_details.is_company', 1)
            ->whereHas('user', function ($query) use ($q) {
                if ($q && $q != '')
                    $query->where('name', 'like', "%$q%")
                          ->orWhere('email', 'like', "%$q%");
            })
            ->orderBy('users.name', 'asc')
            ->select('user_details.*')
            ->paginate(10);

        return UserDetailResource::collection($users);
    }

    /**
     * Delete the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $user_detail = UserDetail::findOrFail($id);
        $user = User::findOrFail($user_detail->user_id);

        if ($user_detail->delete() && $user->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'User successfully deleted!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting user!'
            ], 201);
        }
    }
}
