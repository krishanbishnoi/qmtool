<?php
namespace App\Imports;
use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Auth;
class UploadPktScores implements ToModel
{
    Public $all_qa;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
     /*    $this->dates = array(); */
        // $this->all_qa = User::where('reporting_user_id',Auth::user()->id)->pluck('email','id')->toArray();
        $this->all_qa = User::whereHas('roles', function ($query) {
            $query->where('name', '=', 'qa');
        })->pluck('email','id')->toArray();
    }
    /**
    * @param array $row
    */
    public function model(array $row)
    {        
        if ($row[0] != null) {
            if(array_search($row[0],$this->all_qa)){
                $data = User::find(array_search($row[0],$this->all_qa));
                $data->pkt_scores_1 = $row[1];            
                $data->pkt_scores_2 = $row[2];            
                $data->pkt_scores_3 = $row[3];            
                $data->pkt_scores_4 = $row[4];            
                $data->qa_target = $row[5];      // Minimum Target     
                $data->max_target = $row[6];            // Maximum Amount
                $data->incentive_amount = $row[7];            
                $data->save();
            }
        }
    }
}
