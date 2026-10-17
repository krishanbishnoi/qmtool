<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocationMapping extends Model
{   
	public $timestamps=true; 
	
    protected $fillable = [
        'client_id','location_id'
    ];

}
