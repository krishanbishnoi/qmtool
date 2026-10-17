<?php

namespace App\Imports;

use App\User;
use App\TrainingPktAuditor;
use Maatwebsite\Excel\Concerns\ToModel;
use Auth;
use DateInterval;
use DateTime;
use DatePeriod;


class TrainingPktImport implements ToModel
{
    /* Public $dates; */
    Public $all_qa;
  

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
     /*    $this->dates = array(); */
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

        
        if ($row[0] != null) {
           
            if(array_search($row[0],$this->all_qa)){
               
                $qa_id = User::select('id')->where('email',$row[0])->first();
                
                $check_multiples = TrainingPktAuditor::where('qa_id',$qa_id->id)->where('pkt_month',$row[1])->get();

                if(sizeof($check_multiples) == 0 && gettype($row[1]) != 'NULL' && gettype($row[2]) != 'NULL'){

                    $data = new TrainingPktAuditor;
                    $data->qa_id = $qa_id->id;
                    $data->qa_created_by = Auth::user()->id;
                    $data->pkt_month = $row[1];
                    $data->count_of_test = $row[2];
                    $data->test_attendent = $row[3];
                    $data->overall_marks_obtain = $row[4];
                    $data->out_of_total_marks = $row[5];
                    $data->avg_score = $row[6];
                    $data->save();
                    
                }
              
            }
        }

        
    }
}
