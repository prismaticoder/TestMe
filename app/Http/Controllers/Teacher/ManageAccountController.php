<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;

class ManageAccountController extends Controller
{
    /**
     * Get manage accounts index page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('teacher.manage-account');
    }
}
