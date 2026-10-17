<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProcessMapping extends Model
{   
	public $timestamps=true; 
	
    protected $fillable = [
        'client_id','process_id'
    ];

}
