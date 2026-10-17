<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RcaTwo2 extends Model
{
    public function rcatwo3()
    {
        return $this->hasMany('App\RcaTwo3','rcatwo2_id');
    }
}
