<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RcaTwo1 extends Model
{
    public function rcatwo2()
    {
        return $this->hasMany('App\RcaTwo2','rcatwo1_id');
    }
}
