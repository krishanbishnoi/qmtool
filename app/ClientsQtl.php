<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientsQtl extends Model
{
    public function qtl_user()
    {
        return $this->belongsTo('App\User');
    }
}
