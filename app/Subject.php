<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{

    public function scores() {
        return $this->hasMany(Score::class);
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function options() {
        return $this->hasManyThrough(Option::class, Question::class);
    }

    public function classes() {
        return $this->belongsToMany(Classes::class, 'questions');
    }
}
