<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Log;
use App\Role;
use App\Admin;
use App\Subject;
use App\Classes;
use Gate;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\Controller;

class AdminSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Gate::denies('superAdminGate')){
            $roles = Role::get();
          //  $roles = $admins->roles->role;

        return view('admin.Admin-Section')->with([
           'roles'=>$roles
        ]);
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $newAdmin = new Admin;
        $newAdmin->username = $request->input('username');
        $newAdmin->password = $request->input('password');
        $newAdmin->subject = $request->input('subject');
        // i didn't added the class , because you said the input should just be the above
        $subjectid = Subject::where('subject_name', $request->input('subject'));
        $newAdmin->adminSubjectId = $subjectid->id;
        $roleid = Role::find(1);
        $newAdmin->adminRoleId = $roleid->id;

        $newAdmin->save();

        return view('admin.Admin-Section');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
   
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
  
        

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        // roles and admin => one to one relationship
        $roles = Role::get();
       // $request->flashOnly($roles->id);
        return view('admin.Admin-Section-edit')->with(
            ['roles' => $roles]
        );

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $roles = Role::get();
        $roles->admins()->dissociate();
    }
}

