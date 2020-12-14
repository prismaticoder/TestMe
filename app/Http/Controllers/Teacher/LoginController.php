<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function show() {
        return view('admin.login');
    }

    public function authenticate(Request $request) {
        $credentials = $request->only('username','password');

        if(auth()->guard('admins')->attempt($credentials)) {
            return redirect()->intended('/admin');
        }

        return view('admin.login')->withErrors('Incorrect Username or Password!');
    }

    public function logout() {
        auth()->guard('admins')->logout();
        return redirect('/admin/login');
    }
}
