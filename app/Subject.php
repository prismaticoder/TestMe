<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $appends = ['subject_id'];
    protected $fillable = ['name', 'slug', 'code'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'class_subject', 'subject_id', 'class_id');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function teacherSubjects()
    {
        //get only classes the admin is teaching for that particular subject
        return $this->hasMany(TeacherSubject::class);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getCodeAttribute($value)
    {
        return strtoupper($value);
    }

    public function getSubjectIdAttribute()
    {
        return $this->attributes['id'];
    }
}
