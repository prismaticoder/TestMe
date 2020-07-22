<?php

namespace App;
use Auth;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password','adminRoleId','adminSubjectId '
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function subjects(){
        return $this->hasMany(Subject::class);
    }

    

    public function role() {
        return $this->belongsTo(Role::class);
        }
    // i link this with gate
    public function superAdminRole($role) {
        $role = Role::where('role', 'superadmin')->first();
            
            $adminrole = $role->id;
            if(Auth::user()->AdminRoleId  == $adminrole ){
                return True;
            }
            return false;
    }
}




