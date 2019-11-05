<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    //
    protected $fillable = ['class_id','subject_id','mark','hours','minutes'];
}
