<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BajajBotCall extends Model
{

    public $table = 'bajaj_bot_calls';

    public function audits()
    {
        return $this->belongsTo('App\Audit');
    }


    protected $fillable = ['audit_id', 'confidenceScore', 'words', 'bot_status'];
}
