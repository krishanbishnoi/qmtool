<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PerameterType extends Model

{

    protected $fillable = [
        'parameter_type', 'parameter_ids',
    ];


    public $timestamps = false;

}
