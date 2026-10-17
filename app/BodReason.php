<?php

namespace App;
use App\Scopes\RawDumpsActiveScope;
use Illuminate\Database\Eloquent\Model;

class BodReason extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new RawDumpsActiveScope);
    }
}
