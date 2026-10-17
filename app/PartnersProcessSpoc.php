<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartnersProcessSpoc extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function process()
    {
        return $this->belongsTo('App\Process');
    }
    public function partner()
    {
        return $this->belongsTo('App\Partner');
    }
}
