<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = 'classes';

    public function students() {
        return $this->hasMany(User::Class, 'class_id');
    }
}
