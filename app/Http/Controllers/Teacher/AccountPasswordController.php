<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountPasswordController extends Controller
{
    /**
     * Update a logged in user's account password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'old_password' => ['required', 'string'],
            'new_password' => ['required', 'string'],
        ]);

        if (! Hash::check($request->old_password, auth()->user()->password)) {
            return $this->sendErrorResponse("Password passed does not match existing password", 400);
        }

        auth()->user()->update([
            'password' => $request->new_password
        ]);

        return $this->sendSuccessResponse("Password update successful!");
    }

    /**
     * Verify a user's password ia accurate
     *
     * @param Request $request
     * @return void
     */
    public function verify(Request $request) {
        $request->validate([
            'password' => ['required', 'string']
        ]);

        return response()->json([
            'password_is_valid' => Hash::check($request->password, auth()->user()->password)
        ]);
    }
}
