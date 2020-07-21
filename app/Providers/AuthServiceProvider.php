<?php
use App\Admin;
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


            if(Auth::user()->AdminRoleId  == null ){
                return True;
            }
            return false;
        });
    }
}


