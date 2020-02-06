<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserDetails;
use App\Providers\RouteServiceProvider;
use App\UserDetail;
use App\UserType;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;
    protected function redirectTo()
    {
        $authed = User::find(auth()->user()->id);

        if ($authed->details->type->id == UserType::ADMIN_USER_TYPE) {
            return RouteServiceProvider::ADMIN;
        }
        else if ($authed->details->type->id == UserType::COMPANY_USER_TYPE) {
            return RouteServiceProvider::COMPANY;
        }

        return '/home';
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Gets currently logged in user data
     *
     * @return $user
     */
    public function auth() {
        $authed = UserDetail::where('user_id', auth()->user()->id)->get(1);
        dd($authed);
        return $authed;
    }
}
