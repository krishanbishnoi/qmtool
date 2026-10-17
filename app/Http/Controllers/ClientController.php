<?php

namespace App\Http\Controllers;
ini_set('memory_limit', '-1');
use App\Client;
use App\ClientAdmin;
use App\ClientsQtl;
use App\Mail\UserCreated;
use App\Partner;
use App\Role;
use App\User;
use Auth;
use Crypt;
use Illuminate\Http\Request;
use Mail;
use Storage;
use Validator;
use App\PartnerLocation;
use App\PartnersProcess;



class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Client::where('company_id',Auth::user()->company_id)->with(['process_owner','partners'])->get();
        return view('client.list',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $all_process_owner = User::where('company_id',Auth::user()->company_id)->whereHas('roles', function ($query) {
            $query->where('name', '=', 'process-owner');
        })->pluck('name','id');

         $all_qtl = User::where('company_id',Auth::user()->company_id)->whereHas('roles', function ($query) {
            $query->where('name', '=', 'qtl');
        })->pluck('name','id');
         
        return view('client.create',compact('all_process_owner','all_qtl'));
    }

    public function getDataProcess($client_id) {
        $get=Partner::where('client_id',$client_id)->get();
        $html="";
        $pro=array();
        foreach ($get as $key => $v) {
            $temp_all_process = PartnersProcess::where('partner_id',$v->id)->with('process')->get();
            foreach($temp_all_process as $value) {
                if(!in_array($value->process->name,$pro)) { 
                     $html.="<option value='".$value->process_id."'>".$value->process->name."</option>";
                     $pro[]=$value->process->name;
                }
               
            }
            
        }
        return $html;
    }

    public function getDataLocation($client_id) {
        $get=Partner::where('client_id',$client_id)->get();
        $html="";
        $loc=array();
        foreach ($get as $key => $v) {
            $temp_all_locations = PartnerLocation::where('partner_id',$v->id)->with('location_detail')->get();
            foreach($temp_all_locations as $value) {
                if(!in_array($value->location_detail->name,$loc)) {
                    $html.="<option value='".$value->location_id."'>".$value->location_detail->name."</option>";
                    $loc[]=$value->location_detail->name;
                }
                
            }
            
        }
        return $html;
    }

    public function getDataPartner($client_id) {
        $get=Partner::where('client_id',$client_id)->get();
        $html="";
        foreach ($get as $key => $value) {
            $html.="<option value='".$value->id."'>".$value->name."</option>";
        }
        return $html;
    }

    public function getClientList($process_owner) {
        $get=Client::get();
        $html="<option value='0'>Select Parent Client</option>";
        foreach ($get as $key => $value) {
            $html.="<option value='".$value->id."'>".$value->name."</option>";
        }
        return $html;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);

        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed',
            'mobile' => 'required',
            'qc_time'=>'required|integer',
            'rebuttal_time'=>'required|integer',
            're_rebuttal_time'=>'required|integer',
        ]);

        if($validator->fails())
        {
            return redirect('client/create')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
            //create user
            $temp_roles = '';
            $new_rec = new User;
            $new_rec->company_id = Auth::user()->company_id;
            $new_rec->name = $request->user_name;
            $new_rec->email = $request->email;
            $new_rec->mobile = $request->mobile;
            $new_rec->password = bcrypt($request->password);
            $new_rec->status=1;
            $new_rec->save();
            // attach role
                $role  = Role::where('name','client')->first();
                $temp_roles .= $role->display_name;
                $new_rec->attachRole($role);
            //create user
            if($new_rec->id)
            {
                $new_rc = new Client;
                $new_rc->company_id = Auth::user()->company_id;
                $new_rc->name = $request->name;
                $new_rc->details = $request->details;
                //$new_rc->spoc_user_id = $new_rec->id;
                $new_rc->business_type = $request->business_type;
                $new_rc->holiday = $request->holiday;
                $new_rc->process_owner_id = $request->process_owner_id;
                $new_rc->qc_time = $request->qc_time;
                $new_rc->rebuttal_time = $request->rebuttal_time;
                $new_rc->re_rebuttal_time = $request->re_rebuttal_time;

                if(isset($request->rca_enabled))
                    $new_rc->rca_enabled = 1;
                else
                    $new_rc->rca_enabled = 0;

                if(isset($request->rca_two_enabled))
                    $new_rc->rca_two_enabled = 1;
                else
                    $new_rc->rca_two_enabled = 0;

                if($request->logo) {
                $request->logo->store("company/_".Auth::user()->company_id.'/client');
                $new_rc->logo = $request->logo->hashName();
                }   

                $new_rc->save();


                if($new_rc->id)
                {
                    $new_client_admin = new ClientAdmin;
                    $new_client_admin->client_id = $new_rc->id;
                    $new_client_admin->user_id = $new_rec->id;
                    $new_client_admin->save();

                    foreach ($request->qtl_id as $key => $value) {
                        $new_rec_cq = new ClientsQtl;
                        $new_rec_cq->company_id = Auth::user()->company_id;
                        $new_rec_cq->client_id = $new_rc->id;
                        $new_rec_cq->qtl_user_id = $value;
                        $new_rec_cq->save();
                    }
                    $mail_data = ['name'=>$request->user_name,'roles'=>$temp_roles,'url'=>url('/login'),'company_name'=>Auth::user()->company->company_name,'email'=>$request->email,'password'=>$request->password];
                    //Mail::to($new_rec)->send(new UserCreated($mail_data));
                }
            }

            
        }
        return redirect('client')->with('success', 'Client created successfully');    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $all_client_users = User::whereHas('roles', function ($query) {
            $query->where('name', '=', 'client');
        })->pluck('name','id');

        $attached_client_admin = ClientAdmin::where('client_id',Crypt::decrypt($id))->pluck('user_id','id');
        //dd($attached_client_admin);
        

        $data = Client::with(['clients_qtl','spoc_user','process_owner'])->find(Crypt::decrypt($id));
        $all_process_owner = User::where('company_id',Auth::user()->company_id)->whereHas('roles', function ($query) {
            $query->where('name', '=', 'process-owner');
        })->pluck('name','id');

        $all_qtl = User::where('company_id',Auth::user()->company_id)->whereHas('roles', function ($query) {
            $query->where('name', '=', 'qtl');
        })->pluck('name','id');

        $selected_qtls = $data->clients_qtl->pluck('qtl_user_id');

        return view('client.edit',compact('data','all_process_owner','all_qtl','selected_qtls','all_client_users','attached_client_admin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'process_owner_id' => 'required',
            'business_type' => 'required',
            'name' => 'required',
            'qc_time'=>'required|integer',
            'rebuttal_time'=>'required|integer',
            're_rebuttal_time'=>'required|integer',
        ]);

        if($validator->fails())
        {
            return redirect('client/create')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
                $new_rc = Client::find(Crypt::decrypt($id));
                $new_rc->name = $request->name;
                $new_rc->details = $request->details;
                $new_rc->business_type = $request->business_type;
                $new_rc->process_owner_id = $request->process_owner_id;
                $new_rc->qc_time = $request->qc_time;
                $new_rc->holiday = $request->holiday;
                $new_rc->rebuttal_time = $request->rebuttal_time;
                $new_rc->re_rebuttal_time = $request->re_rebuttal_time;

                if(isset($request->rca_enabled))
                    $new_rc->rca_enabled = 1;
                else
                    $new_rc->rca_enabled = 0;

                if(isset($request->rca_two_enabled))
                    $new_rc->rca_two_enabled = 1;
                else
                    $new_rc->rca_two_enabled = 0;


                if($request->logo) {
                Storage::delete("company/_".Auth::user()->company_id."/client/".$new_rc->logo);
                $request->logo->store("company/_".Auth::user()->company_id.'/client');
                $new_rc->logo = $request->logo->hashName();
                }   

                $new_rc->save(); 
                $cid = Crypt::decrypt($id);
                ClientAdmin::where('client_id',$cid)->delete();
                foreach ($request->client_admin as $key => $value) {
                    $newcar = new ClientAdmin;
                    $newcar->client_id = $cid;
                    $newcar->user_id = $value;
                    $newcar->save();
                }


                ClientsQtl::where('client_id',$new_rc->id)->delete();
                foreach ($request->qtl_id as $key => $value) {
                        $new_rec_cq = new ClientsQtl;
                        $new_rec_cq->company_id = Auth::user()->company_id;
                        $new_rec_cq->client_id = $new_rc->id;
                        $new_rec_cq->qtl_user_id = $value;
                        $new_rec_cq->save();
                    }
        }
        return redirect('client')->with('success', 'Client updated successfully.');    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Client::find(Crypt::decrypt($id));
        //User::find($data->spoc_user_id)->delete();
        Storage::delete("company/_".Auth::user()->company_id."/client/".$data->logo);
        $data->delete();

        return redirect('client')->with('success', 'Client deleted successfully.');    
    }

    public function clients_partner(Request $request)
    {   
        $client_data = Client::find(Crypt::decrypt($request->client_id));
        $data = Partner::where('client_id',Crypt::decrypt($request->client_id))->get();
        return view('partners.list',compact('data','client_data'));
    }
    
    public function audit_list()
    {
        return view('qm_sheet.audit_list');
    }
    public function get_client_all_process($client_id)
    {
        $all_partners = Partner::where('client_id',$client_id)->with('partner_process')->get();
        $process_list = [];
        foreach ($all_partners as $key => $value) {
            foreach ($value->partner_process as $keyb => $valueb) {
                $process_list[$valueb->process_id] = $valueb->process->name;
            }
        }

        return response()->json(['status'=>200,'message'=>"Success",'data'=>$process_list], 200);
    }
}
