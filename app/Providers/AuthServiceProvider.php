<?php
use App\Admin;
use App\Role;
namespace App\Providers;
use Auth;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //to define gate for authorizing admin that has different roles
        Gate::define('superAdminGate', function(){
            $role = Role::where('role', 'superadmin')->first();
            
            $adminrole = $role->id;
            if(Auth::user()->AdminRoleId  == $adminrole ){
                return True;
            }
            return false;
        });
    }
}


