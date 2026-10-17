<?php
namespace App\Http\Controllers;
ini_set('memory_limit', '-1');
use App\Audit;
use App\AuditAlertBox;
use App\AuditParameterResult;
use App\AuditResult;
use App\Auditcycle;
use App\Partner;
use App\QmSheet;
use App\RawData;
use App\Process;
use App\Rca2Mode;
use App\RcaMode;
use App\RcaType;
use Carbon\Carbon;
use App\Reason;
use App\ReasonType;
use App\Sampling;
use App\TmpAudit;
use App\TmpAuditParameterResult;
use App\TmpAuditResult;
use App\TypeBScoringOption;
use Auth;
use Crypt;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\RandomizerSample;
use App\RandomizerReport;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\TataAiaSampling;
use App\FinalOutput;
use App\AuditLog;
use DateTime;
use App\FailedRawDumpSlot;

class RandomizerController extends Controller
{

    public function randomizer_report(Request $request){
 
        //$all_process = Process::select('processes.name','processes.id')->join('qm_sheets','qm_sheets.process_id','=','processes.id')->groupBy('processes.id')->get();
        $all_process = Process::select('processes.name','processes.id')->join('tata_aia_sampling','tata_aia_sampling.process_id','=','processes.id')->groupBy('processes.id')->get();
       
        if($request->isMethod('post')){
            $temp_data = explode(' - ', $request->date_range);
            ////print_r(json_encode($temp_data)); die;
            
          
            $client_id = 9;
            $yesterday = Carbon::now()->subDays(1);
            $day_before_yesterday = Carbon::now()->subDays(2);
           $start_date = date_to_db($temp_data[0]);
           $end_date = date_to_db($temp_data[1]);

                // $top_volume_disposition = RawData::selectRaw('disposition as dispo','count(disposition) as counts')
                //                         ->where('created_at','>=',$start_date)
                //                         ->where('created_at','<=',$end_date)
                //                         ->groupBy('disposition')
                //                         ->orderBy('counts','desc')->pluck('dispo');
                $top_volume_disposition = DB::select("SELECT call_sub_type as dispo FROM raw_data where process_id = 26 group by call_sub_type order by count(call_sub_type) desc limit 1");
              // $disposition = json_encode($top_volume_disposition);
             //print_r($top_volume_disposition[0]->dispo);die;
                $voc_score = DB::select("SELECT info_4 as voc FROM raw_data where process_id = 26 and info_4 is not null group by info_4
                order by count(info_4) desc limit 1");
                //echo ($voc_score[0]->voc); die;
               // $voc_type = json_encode($voc_score);
               //and call_duration >=600 and rand() <= 0.05 and call_duration <=100 and rand() <= 0.05
               $sampling_list = TataAiaSampling::where('id',1)->first();
               $ftr= "0.$sampling_list->ftr";
              
               $nftr = "0.$sampling_list->nftr";
               $top_volume = "0.$sampling_list->top_volume";
               $random_sample ="0.$sampling_list->random_samples";
               $high_aht = "0.$sampling_list->high_aht";
               $low_aht = "0.$sampling_list->low_aht";
               $voc_data ="0.$sampling_list->voc_score";
             
               $batch_id = FailedRawDumpSlot::where('uploader_id',518)->where('process_id',26)->orderBy('id','desc')->first();
                
               if($batch_id){
                
               } else {
                echo "Data not found";
                die;
               }
               $condition1 = "select id,call_id,client_id FROM raw_data where created_at>= '".$start_date."' 
               and created_at <= '".$end_date."' and status = 0 and process_id = 26 and batch_id =  '".$batch_id->id."' ";
               if($sampling_list->ftr !=0 && $sampling_list->nftr !=0 ){
               $condition1 .= " and call_type = 'ftr' and (rand() <= '".$ftr."' and batch_id =  '".$batch_id->id."')";
               $condition1 .= " or call_type = 'nftr' and (rand() <='".$nftr."' and batch_id =  '".$batch_id->id."')";
               }
               if($sampling_list->top_volume != 0){
               $condition1 .= " and disposition = '".$top_volume_disposition[0]->dispo."'";
               $condition1 .= " and (rand()<= '".$top_volume."' and batch_id =  '".$batch_id->id."')" ;
               }    
               if($sampling_list->voc_type){
                    $voc_type =  json_decode($sampling_list->voc_type);
                    $arr = [];
                    foreach($voc_type as $value){
                        if($value == "high"){
                            array_push($arr,4,5);
                        } else if($value == "low"){
                            array_push($arr,0,1,2);
                        } else {
                            array_push($arr,3);
                        }
                    }
                    //echo gettype($voc_type);
                    $voc_vlues = implode(',', $arr);
                    //die;
               $condition1 .= " and info_4 in (".$voc_vlues.")";
               //$condition1 .= " and rand()<='".$voc_data."'";
               }

               $condition1 .= " and call_duration >= ".$sampling_list->low_aht."";
               $condition1 .= " and call_duration <=".$sampling_list->high_aht."";
               if($sampling_list->random_samples != 0){

               $condition1 .= " or (rand() <= '".$random_sample."' and batch_id =  '".$batch_id->id."')";
               }
             
                 //echo $condition1; die;

                $data = DB::select($condition1);
                
              
                // $data = DB::select("select id,call_id,client_id FROM raw_data where created_at>= '".$start_date."' 
                // and created_at <= '".$end_date."' and status = 0 and process_id = 26
                // and disposition = '".$top_volume_disposition[0]->dispo."' and rand()<= '".$top_volume."'  
                // and info_4 = '".$voc_score[0]->voc."' and rand()<='".$voc_data."' and call_duration >= ".$low_aht." and call_duration <=".$high_aht."
                //  and call_type = 'ftr' and rand() <= '".$ftr."' or call_type = 'nftr' and rand() <='".$nftr."' order by rand() <='".$random_sample."' ");
                //print_r(count($data)); die;
                $sample = new RandomizerSample();
                $sample->client_id = $client_id;
                $sample->process_id =26;
                $sample->save();
              
                if($sample->id){

                    foreach($data as $value){
                        DB::table('randomizer_report')->insert([
                            'raw_data_id' => $value->id,
                            'call_id' => $value->call_id,
                            'client_id' => $value->client_id, 
                            'process_id' => 26,
                            'sample_id' =>$sample->id
                        ]);
                    // $randomizer_output = new RandomizerReport();
                    // $randomizer_output->raw_data_id = $value->id;
                    // $randomizer_output->call_id = $value->call_id;
                    // $randomizer_output->client_id = $value->client_id;
                    // $randomizer_output->process_id = $request->process_id;
                    // $randomizer_output->sample_id = $sample->id;
                    // $randomizer_output->save();
                    }
                }
               // print_r(count($data)); die;
               
                
                $all_samples = RandomizerSample::select('randomizer_sample.id','randomizer_sample.process_id','randomizer_sample.created_at','processes.name','randomizer_sample.auditor_qa_status')->join('processes','randomizer_sample.process_id','=','processes.id')->withCount('final_sample','total_sample')->orderBy('id','desc')->limit(50)->get();
                //print_r(print_r($all_samples)); die;
                $index = count($all_samples);
                return view('randomizer.randomizer',compact('all_samples','all_process','index'));

        }else{
            // $data =[];
            $all_samples = RandomizerSample::select('randomizer_sample.id','randomizer_sample.process_id','randomizer_sample.created_at',
            'processes.name','randomizer_sample.auditor_qa_status')->join('processes','randomizer_sample.process_id','=','processes.id')
            ->withCount('final_sample','total_sample')->orderBy('id','desc')->limit(50)->get();
            $index = count($all_samples);
            return view('randomizer.randomizer',compact('all_samples','all_process','index'));
        }
    }


    public function qa_allocation(Request $request){

        
        //$id = $request->sample_id;
        $all_samples = RandomizerSample::withCount('final_sample')->orderBy('id','desc')->limit(1)->get();
        $qa = User::where('reporting_user_id',Auth::user()->id)->get();
      //print_r(json_encode($all_samples)); die;

        if(isset($request->submit)){
            $sample_id = $request->sample_id;
            $distribution = $request->materialExampleRadios;
            $qa_id = $request->qa_id;
        
            $qa_allocation = $request->allocated;

            if($sample_id > 0){
              //print_r($qa_allocation); die;
                if($distribution == "unqual"){

                    $sample_counts = FinalOutput::where('sample_id',$sample_id)->count();
                    $sum = 0;
                    foreach($qa_allocation as $value){      
                        $sum+=$value;
                    
                    }
                
                    //  print_r($sum); die;

                    if(($sum == $sample_counts)){
                        $count = 0;
                        DB::table('randomizer_sample')->where('id',$sample_id)->update([
                            'auditor_qa_status' => 1
                        ]);
                        foreach($qa_id as $qa_ids){
                            $allocated = $qa_allocation[$count];
                            $update_qa_allocation = FinalOutput::whereNull('qa_id')->where('sample_id',$sample_id)->limit($allocated)->update(['qa_id' => $qa_ids,'qa_status'=>1]); 
                            $raw_data_ids = FinalOutput::where('qa_id',$qa_ids)->where('sample_id',$sample_id)->pluck('raw_data_id')->toArray(); 
                            $raw_data_update = RawData::whereIn('id',$raw_data_ids)->update(['qa_id' => $qa_ids]);
                            $count++;
                            
                        }
                    
                    } else {
                        return redirect('qa_allocation')->with('warning', 'Please check again and distribute accordingly'); 
                    }

                }else{

                    $sample_counts = FinalOutput::where('sample_id',$sample_id)->count();
                    $qa_count = sizeof($qa_id);
                    $allocated = (int)($sample_counts/$qa_count);
                    DB::table('randomizer_sample')->where('id',$sample_id)->update([
                        'auditor_qa_status' => 1
                    ]);
                    foreach($qa_id as $qa_ids){
                        //$allocated = $qa_allocation[$count];
                        $update_qa_allocation = FinalOutput::whereNull('qa_id')->where('sample_id',$sample_id)->limit($allocated)->update(['qa_id' => $qa_ids]); 
                        
                        $raw_data_ids = FinalOutput::where('qa_id',$qa_ids)->where('sample_id',$sample_id)->pluck('raw_data_id')->toArray(); 
                        $raw_data_update = RawData::whereIn('id',$raw_data_ids)->update(['qa_id' => $qa_ids]);
                        
                        //$count++;
                    }
                
                }
            
                return redirect('qa_allocation')->with('success', 'Distribution Allocated Successfully.'); 

            } else {
                return redirect('qa_allocation')->with('warning', 'Please Select Sample'); 
            }
            
            
        }
        
        return view('randomizer.qa_allocation',compact('all_samples','qa'));


    }
    public function all_samples($sample_id){
        $id = Crypt::decrypt($sample_id);
        $sample = FinalOutput::where('sample_id',$id)->count();
       // echo $sample; die;
        $data = DB::select("select * FROM final_outputs fo inner join raw_data rd on fo.raw_data_id=rd.id inner join users u on u.id = fo.qa_id 
        where fo.sample_id = '".$id."'");
   
    //    $data = DB::select("select * FROM randomizer_report rr inner join raw_data rd on rr.raw_data_id=rd.id inner join final_outputs fo 
    //    on fo.sample_id = rr.sample_id inner join users u on u.id = fo.qa_id where fo.sample_id = '".$id."' and rr.assign_qa_status = 1");
    // $data = FinalOutput::where('sample_id',$id)
    // ->whereHas('sample', function (Builder $query){
    //     $query->where('assign_qa_status', 1);
    // });
   //print_r(count($data)); die;
      
        return view('randomizer.view_samples',compact('data','sample_id'));
    }

    public function final_output(Request $request){
        $id = $request->sample_id;
        $total_count = $request->total_count;
       // echo $id; die;
          $required_count = $request->required_count;
        if($required_count <= $total_count){
            $data_1 = DB::select("select rd.id as raw_data, rd.call_id, rd.client_id, rd.process_id FROM raw_data rd inner join 
            randomizer_report rr on rd.id=rr.raw_data_id where rr.sample_id = '".$id."' order by rand() limit ".$required_count." ");
        
            foreach($data_1 as $value){
                DB::table('final_outputs')->insert([
                    'raw_data_id' => $value->raw_data,
                    'call_id' => $value->call_id,
                    'client_id' => $value->client_id, 
                    'process_id' => $value->process_id,
                    'sample_id' =>$id
                ]);

                DB::table('randomizer_report')->where('raw_data_id',$value->raw_data)->update([
                    'assign_qa_status' => 1
                ]);
            }
        
    
            return redirect('randomizer_report')->with('success', 'Randomizer saved Successfully.'); 
        }
        else{
            return redirect('randomizer_report')->with('warning', 'Please Do Not Assign Samples More Than Randomizer Count'); 
        }
    }  

   


   
    public function index(){


        $sampling_id = TataAiaSampling::pluck('id');
        $sampling_list = TataAiaSampling::where('id',1)->first();
        $all_process = Process::select('processes.name','processes.id')->join('tata_aia_sampling','tata_aia_sampling.process_id','=','processes.id')->groupBy('processes.id')->get();
    
        $voc_score = DB::select("SELECT info_4 as voc, count(info_4) FROM raw_data where process_id = ".$all_process[0]->id." 
        and info_4 is not null group by info_4
        order by count(info_4) desc");
        $voc_type = $voc_score[0]->voc;
        // $selected_process = json_encode($all_process[0]->name);print_r(json_encode($selected_process)); die;
        $selected_process_id = json_decode($sampling_list->process_id);
        $selected_call_type = json_decode($sampling_list->call_type,true);
        $all_call_type = RawData::where('process_id',26)->where('client_id',9)->distinct('call_type')->pluck('call_type')->toArray();
        $voc_data = RawData::where('process_id',26)->where('client_id',9)->distinct('info_4')->pluck('info_4')->toArray();
        $disposition =  RawData::where('process_id',26)->where('client_id',9)->distinct('call_sub_type')->pluck('call_sub_type');
        $selected_disposition = json_decode($sampling_list->disposition,true);
        $selected_voc = json_decode($sampling_list->voc_type,true);
        //print_r($selected_voc); die;
        $top_volume_disposition = DB::select("SELECT call_sub_type as dispo, count(call_sub_type) as counts FROM raw_data where process_id = 26 group by call_sub_type order by count(call_sub_type) desc ");
        $top_agents = DB::select("SELECT emp_id as agent, count(emp_id) as counts FROM raw_data where process_id = 26 group by emp_id order by count(emp_id) desc limit 4 ");
        $selected_top_agents = json_decode($sampling_list->top_agents,true);
        //print_r(json_encode($top_volume_disposition)); die;
     
        $top_volume = $top_volume_disposition[0]->dispo;
        return view('randomizer.add_new_sampling',compact('all_process','sampling_id','top_agents','selected_top_agents','sampling_list','voc_type','selected_process_id','disposition','selected_disposition','selected_call_type','all_call_type','voc_data','selected_voc','top_volume'));
    }
 function get_call_type(){
        $all_call_type = RawData::where('process_id',26)->where('client_id',9)->distinct('call_type')->pluck('call_type');
        
        $disposition =  RawData::where('process_id',26)->where('client_id',9)->distinct('call_sub_type')->pluck('call_sub_type');
        $voc_data = RawData::where('process_id',26)->where('client_id',9)->distinct('info_4')->pluck('info_4');
        // print_r(json_encode($voc_data)); die;
        return response()->json(['status'=>200,'message'=>"Success",'data1'=>$all_call_type,'data2'=> $disposition,'data3' =>$voc_data], 200);
    }
    // public function save_sampling(Request $request){
    //     //$all_data = $request->all();
    //     //dd($request->all())
    
    //     //echo json_encode($qa); die;
    //     $check_sampling = TataAiaSampling::where('name',$request->name)->count();
    //     if($check_sampling){
           
    //         return redirect('randomizer/add_new_sampling')->with('warning', 'Please enter different name.');
    //     }
    //     else {
    //         $new_sampling = new TataAiaSampling;
    //         $new_sampling ->process_id = json_encode($request['process']);
    //         if(Auth::user()->hasRole('qtl'))
    //         $new_sampling->tl=Auth::user()->id;
    //         else if(Auth::user()->hasRole('client'))
    //         $new_sampling->client_id=Auth::user()->id;
    //         $new_sampling ->call_type = json_encode($request['call_type']);
    //         $new_sampling->voc_type = json_encode($request['voc_type']);
    //         $new_sampling->voc_score = json_encode($request['random_no']);
    //         $new_sampling->random_samples = json_encode($request['random_samples']);
    //         $new_sampling->name = $request->name;
    //         $new_sampling->description = $request->description;
    //         $new_sampling->sample_id = 0;
    //         $new_sampling->save();
            
    //         $update = User::whereIn('id',$request->qa)->update(['sampling_id'=>$new_sampling->id]);
    //     }
    //     return redirect('randomizer/add_new_sampling')->with('success', 'Sampling recorded successfully.'); 
    //    }

       public function save_sampling(Request $request){
        $new_sampling = TataAiaSampling::find($request->sampling_id);
 
        if(Auth::user()->hasRole('qtl'))
        $new_sampling->tl=Auth::user()->id;
        else if(Auth::user()->hasRole('client'))
        $new_sampling ->process_id = json_encode($request['process']);
       // print_r(json_encode($request['voc_type'])); die;
        $new_sampling->top_volume = $request['top_volume'];
        $new_sampling->client_id=Auth::user()->id;
        $new_sampling ->call_type = json_encode($request['call_type']);
        $new_sampling->voc_type = json_encode($request['voc_type']);
        $new_sampling->voc_score = $request['voc_score'];
        $new_sampling->random_samples = $request['random_samples'];
        $new_sampling->ftr = $request['ftr'];
        $new_sampling->nftr = $request['nftr'];
        if(isset($request['low_aht']) && $request['low_aht'] !=0){
            $new_sampling->low_aht = $request['low_aht'];
        } else {
            $new_sampling->low_aht = 0;
        }
            
        if(isset($request['high_aht']) && $request['high_aht'] !=0){
            $new_sampling->high_aht = $request['high_aht'];
        } else {
            $new_sampling->high_aht = 5000;
        }
            
        $new_sampling->name = $request->name;
        $new_sampling->description = $request->description;
        $new_sampling->sample_id = 0;
        $new_sampling->top_agents =json_encode($request['agents']);
        $new_sampling->save();
        return redirect('set')->with('success', 'Randomizer Updated Successfully.'); 
       }

}
