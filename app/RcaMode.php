<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RcaMode extends Model
{
    public function rca1()
    {
        return $this->hasMany('App\Rca1','mode_id');
    }
}
