<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    //
    protected $fillable = ['question_id', 'body', 'is_correct'];
    protected $touches = ['question'];

    public $timestamps = false;

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
