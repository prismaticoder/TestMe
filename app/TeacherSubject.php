<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherSubject extends Model
{
    protected $fillable = ['teacher_id', 'subject_id'];
    protected $appends = ['name','alias'];
    public $timestamps = false;

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function classes() {
        return $this->belongsToMany(Classes::class, 'teachersubject_class', 'teachersubject_id', 'class_id');
    }

    public function getNameAttribute() {
        return $this->subject->name;
    }

    public function getAliasAttribute() {
        return $this->subject->alias;
    }
}
