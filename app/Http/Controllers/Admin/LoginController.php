<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function authenticate(Request $request) {
        $credentials = $request -> only('username','password');

        if(Auth::guard('admins')->attempt($credentials)) {
            return redirect()->intended();
        }

        return view('admin.login')->with('error','Incorrect Login Credentials');
    }

    public function logout() {
        Auth::logout();
        return redirect('/admin/login');
    }
}
