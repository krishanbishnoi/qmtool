<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartnersProcess extends Model
{
    public function partner()
    {
        return $this->belongsTo('App\Partner');
    }
    public function process()
    {
        return $this->belongsTo('App\Process');
    }
}
