<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RandomizerSample extends Model
{
    protected $table = 'randomizer_sample';

      public function final_sample()
    {
      return $this->hasMany('App\FinalOutput','sample_id','id');
    }
    public function process_name()
    {
      return $this->belongsTo('App\Process','process_id','id');
    }
    public function total_sample()
    {
      return $this->hasMany('App\RandomizerReport','sample_id','id');
    }
}
