<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RcaTwo3 extends Model
{
    public function rcatwo2()
    {
        return $this->belongsTo('App\RcaTwo2','rcatwo1_id');
    }
}
