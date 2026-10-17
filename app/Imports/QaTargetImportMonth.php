<?php

namespace App\Imports;

use App\User;
use App\QaTarget;
use Maatwebsite\Excel\Concerns\ToModel;
use Auth;


class QaTargetImportMonth implements ToModel
{
    Public $all_qa;


    public $all_qa_id;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        
        // $this->all_qa = User::where('reporting_user_id',Auth::user()->id)->pluck('email','id')->toArray();
        $this->all_qa = User::where('parent_client',23)->where('status',1)->pluck('email','id')->toArray();

        // $this->all_qa_id = User::where('reporting_user_id',Auth::user()->id)->pluck('id','email')->toArray();
        $this->all_qa_id = User::where('parent_client',23)->where('status',1)->pluck('id','email')->toArray();

       

      
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
             
                /* echo $this->all_qa_id[$row[0]];
                dd(); */
                $check_multiples = QaTarget::where('qa_email',$row[0])->where('target_month',$row[2])->get();

                if(sizeof($check_multiples) == 0){
              
                    $data = $target = new QaTarget;
                    $data->qa_id = $this->all_qa_id[$row[0]];
                    $data->qa_email = $row[0];
                    $data->qa_target = $row[1];
                    $data->updated_by = Auth::User()->id;
                    $data->target_month = $row[2];
                                     
                    $data->save();
                }
                
            }
        }

        
    }
}
