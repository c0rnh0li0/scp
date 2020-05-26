<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserDetails as UserDetailResource;
use App\User;
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
