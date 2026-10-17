<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgentDownTime extends Model
{
   public $table = 'agent_down_time';
   public function auditor()
   {
       return $this->belongsTo('App\User','auditor_id','id');
   }
}
