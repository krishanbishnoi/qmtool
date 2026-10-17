<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FailedRawDumpSlot extends Model
{
    public function client()
    {
        return $this->belongsTo('App\Client');
    }
    public function process()
    {
        return $this->belongsTo('App\Process');
    }
    public function partner()
    {
        return $this->belongsTo('App\Partner');
    }
    public function uploader()
    {
        return $this->belongsTo('App\User','uploader_id');
    }
}
