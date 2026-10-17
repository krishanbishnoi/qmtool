<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rca2 extends Model
{
    public function rca3()
    {
        return $this->hasMany('App\Rca3');
    }
}
