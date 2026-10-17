<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    public function circle()
    {
        return $this->hasMany('App\Circle');
    }
}
