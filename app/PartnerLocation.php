<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartnerLocation extends Model
{
    public function location_detail()
    {
        return $this->belongsTo('App\Region','location_id');
    }
}
