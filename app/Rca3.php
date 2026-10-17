<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rca3 extends Model
{
    public function rca2()
    {
        return $this->belongsTo('App\Rca2');
    }
}
