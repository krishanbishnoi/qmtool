<?php

namespace App\Imports;

use App\FailedRawDumpRow;
use App\MonthTarget;
use App\Region;
use App\FailedMonthTarget;
use App\FailedMonthTargetRow;

use App\User;
use Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;


class MonthlyTargetImport implements ToModel, WithBatchInserts, WithChunkReading
{
    Public $data;
    Public $all_location;
    Public $all_qa;
    Public $failed_rows;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Array $data)
    {
        $this->data = $data;

        // get all company location
        $this->all_location = Region::where('company_id',$this->data['company_id'])->pluck('name','id')->toArray();

       // $this->all_location = Region::where('company_id',$this->data['company_id'])->pluck('name','id')->toArray();
        
        /* $this->all_qa = User::where('company_id',Auth::user()->company_id)->whereHas('roles', function ($query) {
            $query->where('name', '=', 'qa');
        })->pluck('email','id')->toArray(); */
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // print_r($this->all_location);
        // print_r($this->all_qa);

       /*  print_r($this->all_location);
        echo "<br>";
        echo $row[12];
        dd(); */

        if($row[0]!=null && $row[1]!=null && $row[2]!=null && $row[3]!=null && $row[4]!='Week-1' && $row[5]!='Week-2' && $row[0]!='Zone' &&  $row[2]!='Brand')
        
        {   
            /* echo $this->data['dump_date'];
            dd(); */
            /* echo $this->data['lob'];;
            dd(); */


            if(array_search(strtolower($row[1]),array_map('strtolower', $this->all_location) )!=null)
            {

                $exist = MonthTarget::where('month_of_target',$this->data['dump_date'])
                ->where('company_id',$this->data['company_id'])
                ->where('client_id',$this->data['client_id'])
                ->where('partner_id',$this->data['partner_id'])
                ->where('process_id',$this->data['process_id'])

                ->where('zone',$row[0])
                ->where('location',$row[1])
                ->where('brand_name',$row[2])
                ->where('circle',$row[3])

                ->where('lob',$this->data['lob'])->get();

               
                if(sizeof($exist) != 0){

                    
                    $new_rc = MonthTarget::find($exist[0]->id);
                    
                    $new_rc->month_of_target = $this->data['dump_date'];
                    //$new_rc->batch_id = $this->data['batch_id'];
                    $new_rc->company_id = $this->data['company_id'];
                    $new_rc->client_id = $this->data['client_id'];
                    $new_rc->partner_id = $this->data['partner_id'];
                    $new_rc->process_id = $this->data['process_id'];
                    $new_rc->lob = $this->data['lob'];
                    //$new_rc->partner_location_id = array_search($row[5],$this->all_location);
                    //$new_rc->qa_id = array_search($row[17],$this->all_qa);
        
                    $new_rc->zone = $row[0];
                    $new_rc->location = $row[1];
                    $new_rc->brand_name = $row[2];
                    $new_rc->circle = $row[3];
                    
                    $new_rc->rebuttal_raised = 0;
                    $new_rc->rebuttal_percent = 0;
                
                    $new_rc->eq_audit_target_w1 = $row[4];
                    $new_rc->eq_audit_done_w1 = 0;
                    $new_rc->audit_achievement_percent_w1 = 0;
                    $new_rc->eq_score_percent_w1 = 0;
                    $new_rc->fatal_percent_w1 = 0;
        
                    $new_rc->eq_audit_target_w2 = $row[5];
                    $new_rc->eq_audit_done_w2 = 0;
                    $new_rc->audit_achievement_percent_w2 = 0;
                    $new_rc->eq_score_percent_w2 = 0;
                    $new_rc->fatal_percent_w2 = 0;
        
                    $new_rc->eq_audit_target_w3 = $row[6];
                    $new_rc->eq_audit_done_w3 = 0;
                    $new_rc->audit_achievement_percent_w3 = 0;
                    $new_rc->eq_score_percent_w3 = 0;
                    $new_rc->fatal_percent_w3 = 0;
        
                    $new_rc->eq_audit_target_w4 = $row[7];
                    $new_rc->eq_audit_done_w4 = 0;
                    $new_rc->audit_achievement_percent_w4 = 0;
                    $new_rc->eq_score_percent_w4 = 0;
                    $new_rc->fatal_percent_w4 = 0;
        
                    $new_rc->eq_audit_target_mtd = $row[4]+$row[5]+$row[6]+$row[7];
                    $new_rc->eq_audit_done_mtd = 0;
                    $new_rc->audit_achievement_percent_mtd = 0;
                    $new_rc->eq_score_percent_mtd = 0;
                    $new_rc->fatal_percent_mtd = 0;
                    $new_rc->slot_batch_id = $this->data['batch_id'];
        
                    $new_rc->save();
                }

                else {
                    $new_rc = new MonthTarget;
                    $new_rc->month_of_target = $this->data['dump_date'];
                    //$new_rc->batch_id = $this->data['batch_id'];
                    $new_rc->company_id = $this->data['company_id'];
                    $new_rc->client_id = $this->data['client_id'];
                    $new_rc->partner_id = $this->data['partner_id'];
                    $new_rc->process_id = $this->data['process_id'];
                    $new_rc->lob = $this->data['lob'];
                    //$new_rc->partner_location_id = array_search($row[5],$this->all_location);
                    //$new_rc->qa_id = array_search($row[17],$this->all_qa);

                    $new_rc->zone = $row[0];
                    $new_rc->location = $row[1];
                    $new_rc->brand_name = $row[2];
                    $new_rc->circle = $row[3];
                    
                    $new_rc->rebuttal_raised = 0;
                    $new_rc->rebuttal_percent = 0;
                    
                
                    $new_rc->eq_audit_target_w1 = $row[4];
                    $new_rc->eq_audit_done_w1 = 0;
                    $new_rc->audit_achievement_percent_w1 = 0;
                    $new_rc->eq_score_percent_w1 = 0;
                    $new_rc->fatal_percent_w1 = 0;
        
                    $new_rc->eq_audit_target_w2 = $row[5];
                    $new_rc->eq_audit_done_w2 = 0;
                    $new_rc->audit_achievement_percent_w2 = 0;
                    $new_rc->eq_score_percent_w2 = 0;
                    $new_rc->fatal_percent_w2 = 0;
        
                    $new_rc->eq_audit_target_w3 = $row[6];
                    $new_rc->eq_audit_done_w3 = 0;
                    $new_rc->audit_achievement_percent_w3 = 0;
                    $new_rc->eq_score_percent_w3 = 0;
                    $new_rc->fatal_percent_w3 = 0;
        
                    $new_rc->eq_audit_target_w4 = $row[7];
                    $new_rc->eq_audit_done_w4 = 0;
                    $new_rc->audit_achievement_percent_w4 = 0;
                    $new_rc->eq_score_percent_w4 = 0;
                    $new_rc->fatal_percent_w4 = 0;
        
                    $new_rc->eq_audit_target_mtd = $row[4]+$row[5]+$row[6]+$row[7];
                    $new_rc->eq_audit_done_mtd = 0;
                    $new_rc->audit_achievement_percent_mtd = 0;
                    $new_rc->eq_score_percent_mtd = 0;
                    $new_rc->fatal_percent_mtd = 0;
                    $new_rc->slot_batch_id = $this->data['batch_id'];
                    $new_rc->save();
                }
               /*  print_r();



                echo "hiii";
                dd();


             */

            }else
            {
                $reason = "";

                if(array_search($row[1],$this->all_location)!=null){

                } else {
                    $reason .= "wrong location entered. ";
                }

             

                $fr = new FailedMonthTargetRow;
                /* $fr->client_id = $this->data['client_id'];
                $fr->partner_id = $this->data['partner_id']; */
                $fr->zone = $row[0];
                $fr->location = $row[1];
                $fr->brand = $row[2];
                $fr->circle = $row[3];
                $fr->week_1_target = $row[4];
                $fr->week_2_target = $row[5];
                $fr->week_3_target = $row[6];
                $fr->week_4_target = $row[7];
                $fr->slot_batch_id = $this->data['batch_id'];
                $fr->reason = $reason;
                $fr->save();
            }
        }
    }

    public function batchSize(): int
    {
        return 1000;
    }
    
    public function chunkSize(): int
    {
        return 250;
    }
}
