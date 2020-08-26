<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Subject extends Model
{

    public function scores() {
        return $this->hasMany(Score::class);
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function classes() {
        return $this->belongsToMany(Classes::class, 'admin_subject', 'subject_id', 'class_id');
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

    public function getStartedExam($class_id) {
        $today = date('Y-m-d');
        $exam = Exam::where('subject_id',$this->id)->where('class_id',$class_id)->where('date', $today)->where('hasStarted', 1)->first();

        return $exam ? $exam : null;
    }
}



