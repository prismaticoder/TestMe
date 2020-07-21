<?php

namespace App;

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
        'username', 'password','adminClassId','AdminRoleId','adminSubjectId '
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

    public function classes(){
        return $this->hasMany(Classes::class);
    }

    public function roles() {
        return $this->belongsTo(Role::class);
        }
    public function adminRole($role) {
        if($this->roles()->where('id', $role)->first()){
            return True;
        }
        return false;
    }
}




