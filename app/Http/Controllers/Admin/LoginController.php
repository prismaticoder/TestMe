<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Admin;
use Gate;
// use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    // use AuthenticatesUsers;

    // protected $guard = 'admins';

    public function __construct()
    {
        $this->middleware('guest:admins')->except('logout');
    }

    public function showLoginForm() {
        return view('admin.login');
    }

    // protected function guard()
    // {
    //     return Auth::guard($this->guard);
    // }

    public function authenticate(Request $request) {
        $credentials = $request->only('username','password');

        if(Auth::guard('admins')->attempt($credentials)) {
            $Admindata = Admin::where('username',$credentials['username'])->where('password', $credentials['password'])->get();
            session()->put('Adminrole', $Admindata);
            return redirect()->intended(route('dashboard'));
        }

        return view('admin.login')->withErrors('Incorrect Username or Password!');
    }

    public function logout() {
        Auth::guard('admins')->logout();
        return redirect('/admin/login');
    }
}
