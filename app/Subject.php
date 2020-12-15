<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{

    protected $appends = ['subject_id'];
    protected $fillable = ['name', 'alias'];

    public static function findThroughAlias($alias) {
        return self::where(compact('alias'))->firstOrFail();
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function classes() {
        return $this->belongsToMany(Classes::class, 'class_subject', 'subject_id', 'class_id');
    }

    public function teacherSubjects() {
        //get only classes the admin is teaching for that particular subject
        return $this->hasMany(TeacherSubject::class);
    }

    public function getSubjectIdAttribute() {
        return $this->attributes['id'];
    }
}



