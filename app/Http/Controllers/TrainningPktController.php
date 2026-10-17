<?php
namespace App\Http\Controllers;
use App\User;
use App\QaTarget;
use App\TrainingPktAuditor;
use Crypt;
use Auth;
use Illuminate\Http\Request;

class TrainningPktController extends Controller
{
    public function index(){
        
        return view('trainning_pkt.qa_target');
    }

    public function get_list(){

        $data = TrainingPktAuditor::select('training_pkt_auditors.*','users.name')
       ->join('users', 'training_pkt_auditors.qa_id', '=', 'users.id')->
       where('users.reporting_user_id',Auth::user()->id)->get();

       return view('trainning_pkt.list',compact('data'));
   }
}
