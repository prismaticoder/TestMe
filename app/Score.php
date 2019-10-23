<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $table = 'scores';
    protected $fillable = ['class_id','student_id','subject_id','score'];
}
