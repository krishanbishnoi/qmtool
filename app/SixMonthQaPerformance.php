<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SixMonthQaPerformance extends Model
{
   protected $table = 'six_month_qa_performances';


   protected $fillable = [
    'auditor_id',
    'auditor_doj',
    'active_status',
    'month_1',
    'month_2',
    'month_3',        
    'month_4',        
    'month_5',        
    'month_6',        
];
}
