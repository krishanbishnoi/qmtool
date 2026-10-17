<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientAdmin extends Model
{
    public function admin_user()
    {
        return $this->belongsTo('App\User');
    }
    public function client()
    {
        return $this->belongsTo('App\Client');
    }
}
