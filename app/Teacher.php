<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Teacher extends Authenticatable
{
    use Notifiable;

    public const ROLES = [
        'ADMIN' => 1,
        'TEACHER' => 2,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'title', 'username', 'password', 'role_id',
    ];

    protected $appends = ['full_name'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function subjects()
    {
        return $this->hasMany(TeacherSubject::class)
            ->with(['classes' => fn ($q) => $q->orderBy('name')], ['subject' => fn ($q) => $q->orderBy('name')]);
    }

    public function getAccessibleSubjects(): Collection
    {
        return $this->isAdmin()
                ? Subject::orderBy('name')->with(['classes' => fn ($q) => $q->orderBy('name')])->get()
                : $this->subjects;
    }

    public function classes()
    {
        return $this->belongsToMany(Classes::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function scopeNotAdmin($query)
    {
        return $query->where('role_id', self::ROLES['TEACHER']);
    }

    public function scopeAdmin($query)
    {
        return $query->where('role_id', self::ROLES['ADMIN']);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function getFullNameAttribute()
    {
        return strtoupper(
            "{$this->title}. {$this->lastname} {$this->firstname}"
        );
    }

    // i link this with gate
    public function isAdmin()
    {
        return $this->role_id === self::ROLES['ADMIN'];
    }

    public function isTeacher()
    {
        return $this->role_id === self::ROLES['TEACHER'];
    }
}
