<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    //
    protected $fillable = ['question_id','option','isCorrect'];

    public $timestamps = false;
}
