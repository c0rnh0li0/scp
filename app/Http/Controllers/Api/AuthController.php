<?php

namespace App\Http\Controllers\Api;
use App\Http\Resources\UserDetails as UserDetailResource;
use App\Providers\RouteServiceProvider;
use App\UserDetail;
use App\UserType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public $successStatus = 200;

    public function register(Request $request) {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'c_password' => 'required|same:password',
            ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);                        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $success['token'] =  $user->createToken('AppName')->accessToken;

        return response()->json(['success'=>$success], $this->successStatus);
    }

    public function login(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $details = UserDetail::find($user->id);

            $goto = RouteServiceProvider::INDEX;

            switch ($details->user_type_id) {
                case UserType::ADMIN_USER_TYPE:
                    $goto = RouteServiceProvider::ADMIN;
                    break;
                case UserType::TOURIST_USER_TYPE:
                    $goto = RouteServiceProvider::HOME;
                    break;
                case UserType::COMPANY_USER_TYPE:
                    $goto = RouteServiceProvider::COMPANY;
                    break;
                default:
                    $goto = RouteServiceProvider::INDEX;
                    break;
            }

            $success['token'] = $user->createToken($user->name . '@SCP')-> accessToken;
            $success['goto'] = route(str_replace('/', '', $goto));

            return response()->json(['success' => $success], $this->successStatus);
        } else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->token->each(function ($token, $key) {
                $token->delete();
            });
        }

        return response()->json(['response' => [
            'success' => true,
            'goto' => route('index')
        ]], $this->successStatus);
    }

    public function user() {
        $user = Auth::user();
        $details = UserDetail::findOrFail($user->id);
        return new UserDetailResource($details);
        //return response()->json(['success' => $user], $this->successStatus);
    }
}
