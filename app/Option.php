<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    //
    protected $fillable = ['question_id','body','isCorrect'];

    public $timestamps = false;

    public function question() {
        return $this->belongsTo(Question::class);
    }
}
