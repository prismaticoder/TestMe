<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['class_id','subject_id','base_score','hours','minutes','date','hasStarted'];
    //
    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function class() {
        return $this->belongsTo(Classes::class);
    }
}
