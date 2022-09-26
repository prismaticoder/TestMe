<?php

namespace App\Providers;

use App\Admin;
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
        Gate::define('superAdminGate', function ($admin) {
            return $admin->isAdmin();
        });

        Gate::define('access-class-subject', function ($teacher, int $classId, int $subjectId) {
            if ($teacher->isAdmin()) {
                return true;
            }

            $subject = $teacher->subjects()->where('subject_id', $subjectId)->first();

            return $subject ? $subject->classes()->where('class_id', $classId)->exists() : false;
        });
    }
}
