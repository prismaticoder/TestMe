<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Student;
use Illuminate\Http\Request;

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

    public function __construct()
    {
        $this->middleware('auth')->only('logout');
        $this->middleware('guest')->except('logout');
    }

    public function index()
    {
        return view('student.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        $student = Student::where('examination_number', $request->examination_number)->first();

        if (! $student) {
            return redirect('login')->withErrors('Incorrect Login Credentials');
        }

        auth()->login($student);

        return redirect()->intended('/exams');
    }

    /**
     * Validate the user login request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'examination_number' => 'required|string|size:6|alpha_num',
        ]);
    }

    public function logout()
    {
        auth()->logout();

        return redirect('/login');
    }
}
