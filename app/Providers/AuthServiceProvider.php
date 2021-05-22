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
        Gate::define('superAdminGate', function($admin){
            return $admin->isSuperAdmin();
        });

        //admin should only view questions of his own subject
        Gate::define('view-subject-details', function($admin, $subject_id, $class_id) {
            if ($admin->isSuperAdmin()) return true;

            $subject = $admin->subjects()->where('subject_id', $subject_id)->first();
            $check = $subject ? $subject->classes()->where('class_id',$class_id)->first() : false;

            return $check ? true : false;
        });
    }
}


