<?php

namespace App\Imports;

use App\User;
use App\QaDailyTarget;
use Maatwebsite\Excel\Concerns\ToModel;
use Auth;
use DateInterval;
use DateTime;
use DatePeriod;


class QaTargetImportDaily implements ToModel
{
    Public $dates;
    Public $all_qa;
  

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->dates = array();
        $this->all_qa = User::where('reporting_user_id',Auth::user()->id)->pluck('email','id')->toArray();

        $this->all_qa_id = User::where('reporting_user_id',Auth::user()->id)->pluck('id','email')->toArray();
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

            
            
           
        if($row[0] == 'Registered QA email ID'){
            // $this->$array = array();
            $today = date_create(date('yy-m-01'));
            /* echo $today;
            dd(); */
            for($i = 1; $i <= 31; $i ++){
                $tomorrow = date_format($today,"Y-m-d");
                $this->dates[] = $tomorrow;
                date_add($today,date_interval_create_from_date_string("1 days"));
            }
            
        }
       
        /* $current_date = date('Y-m-d');
        echo $current_date;
        dd(); */
        
        if ($row[0] != null) {
           
            if(array_search($row[0],$this->all_qa)){
               
                $qa_id = User::select('id')->where('email',$row[0])->first();
                
                $count = 1;
                foreach($this->dates as $value){
                   /*  echo "hii";
                    dd(); */
                    $check_multiples = QaDailyTarget::where('qa_id',$qa_id->id)->where('target_day',$value)->get();

                    if(date('Y-m-d') <= $value && gettype($row[$count]) != 'NULL'){

                        if(sizeof($check_multiples) != 0){
                            $data = QaDailyTarget::find($check_multiples[0]->id);
                            $data->qa_id = $qa_id->id;
                            $data->qa_created_by = Auth::user()->id;
                            $data->target_day = $value;
                            $data->target = $row[$count];
                       
                            $data->save();
                        }else {
                            $data = new QaDailyTarget;
                            $data->qa_id = $qa_id->id;
                            $data->qa_created_by = Auth::user()->id;
                            $data->target_day = $value;
                            $data->target = $row[$count];
                       
                            $data->save();
                        }
                        
                       
                    }
                    $count ++;
                }
                
                
            }
        }

        
    }
}
