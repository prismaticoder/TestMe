<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';

    public function scores() {
        return $this->hasMany(Score::class);
    }
}
