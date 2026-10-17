<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rca1 extends Model
{
    public function rca2()
    {
        return $this->hasMany('App\Rca2');
    }
}
