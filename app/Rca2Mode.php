<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rca2Mode extends Model
{
	public function rcatwo1()
    {
    	return $this->hasMany('App\RcaTwo1','mode2_id');
    }
}
