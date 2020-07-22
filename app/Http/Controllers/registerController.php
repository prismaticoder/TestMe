<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use registerInstitution;

class registerController extends Controller
{
    //validate user input
    public function registerInstitution(Request $request){
        try {
            $register = new registerInstitution;
            
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
