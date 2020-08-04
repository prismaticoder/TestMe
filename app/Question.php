<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    //Model to interact with the questions table
    protected $fillable = ['exam_id','question'];

    public function options() {
        return $this->hasMany(Option::class);
    }

    public function exam() {
        return $this->belongsTo(Exam::class);
    }
}


