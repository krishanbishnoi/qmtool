<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    public function process_owner()
    {
        return $this->belongsTo('App\User');
    }
    public function spoc_user()
    {
        return $this->belongsTo('App\User');
    }
    public function clients_qtl()
    {
        return $this->hasMany('App\ClientsQtl');
    }
    public function partners()
    {
        return $this->hasMany('App\Partner');
    }
}
