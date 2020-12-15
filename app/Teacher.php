<?php

namespace App;
use Auth;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Teacher extends Authenticatable
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
        'firstname', 'lastname', 'title', 'username', 'password','role_id'
    ];

    protected $appends = ['full_name'];

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
        return $this->isAdmin()
                    ? Subject::orderBy('name')
                        ->with(['classes' => function($q) {
                                return $q->orderBy('name');
                            }
                        ])
                        ->get()
                    : $this->hasMany(TeacherSubject::class)
                        ->with(
                            ['classes' => function($q) {
                                return $q->orderBy('name');
                            }],
                            ['subject' => function($q) {
                                return $q->orderBy('name');
                            }]
                        );
    }

    public function classes(){
        return $this->belongsToMany(Classes::class);
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function scopeNotAdmin($query) {
        return $query->where('role_id', self::ROLES['TEACHER']);
    }

    public function scopeAdmin($query) {
        return $query->where('role_id', self::ROLES['ADMIN']);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function getFullNameAttribute() {
        return strtoupper(
            "{$this->title}. {$this->lastname} {$this->firstname}"
        );
    }

    // i link this with gate
    public function isAdmin() {
        return $this->role_id === self::ROLES['ADMIN'];
    }

    public function isTeacher() {
        return $this->role_id === self::ROLES['TEACHER'];
    }
}




