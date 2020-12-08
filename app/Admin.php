<?php

namespace App;
use Auth;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    public const ROLES = [
        'ADMIN' => 1,
        'TEACHER' => 2
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password','role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // protected $appends = ['admin_subjects'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function subjects(){
        return $this->isAdmin() ? Subject::all() : $this->hasMany(AdminSubject::class)->with('classes','subject');
    }

    public function classes(){
        return $this->belongsToMany(Classes::class);
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function scopeTeacher($query) {
        return $query->where('role_id', self::ROLES['TEACHER']);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    // i link this with gate
    public function isAdmin() {
        return $this->role_id === self::ROLES['ADMIN'];
    }

    public function isTeacher() {
        return $this->role_id === self::ROLES['TEACHER'];
    }
}




