<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartnerMapping extends Model
{	
	public $timestamps=true; 
	
    protected $fillable = [
        'client_id','partner_id'
    ];

}
