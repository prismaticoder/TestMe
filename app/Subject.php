<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Subject extends Model
{

    protected $appends = ['subject_id'];

    public static function findThroughAlias($alias) {
        return self::where(compact('alias'))->firstOrFail();
    }

    public function scores() {
        return $this->hasMany(Score::class);
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function classes() {
        return $this->belongsToMany(Classes::class, 'class_subject', 'subject_id', 'class_id');
    }

    public function adminClasses() {
        //get only classes the admin is teaching for that particular subject
        return $this->hasManyThrough(Classes::class, AdminSubject::class, 'subject_id', 'id', 'id', 'class_id');
    }

    public function adminSubjects() {
        //get only classes the admin is teaching for that particular subject
        return $this->hasMany(AdminSubject::class);
    }

    public function admins() {
        return $this->belongsToMany(Admin::class);
    }

    public function getSubjectIdAttribute() {
        return $this->attributes['id'];
    }

    public function getStartedExam($class_id) {
        $exam = Exam::where('subject_id',$this->id)->where('class_id',$class_id)->where('has_started', 1)->orderBy('date', 'desc')->orderBy('updated_at','desc')->first();

        return $exam ? $exam : null;
    }
}



