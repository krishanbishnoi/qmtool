<?php

namespace App\Http\Controllers;
ini_set('memory_limit', '-1');
use App\Auditcycle;
use App\Client;
use App\Process;
use App\Partner;
use App\PartnersProcess;
use App\QmSheet;

use Illuminate\Http\Request;


use Validator;
use Auth;
use Crypt;

class AuditcycleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Auditcycle::with(['client','process','qmsheet'])->get();;
        return view('audit_cycle/list',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        $client_id = Auth::user()->parent_client;
        $partners = Partner::where('client_id', $client_id)->pluck('id');
        $process = PartnersProcess::whereIn('partner_id', $partners )->pluck('process_id');

        $all_process = Process::whereIn('id',$process)->pluck('name','id');
        $all_qmsheet = QmSheet::where('client_id',$client_id)->pluck('name','id');
        $all_clients = Client::all()->pluck('name','id');

        // $all_clients = Client::all()->pluck('name','id');
        // $all_process = Process::all()->pluck('name','id');
        // $all_qmsheet = QmSheet::all()->pluck('name','id');
        return view('audit_cycle/create',compact('all_clients','all_process','all_qmsheet'));
        
    }

    
    public function get_client_process_qmsheeet_list($process_id){

        $client_id = Auth::user()->parent_client;

        $temp_list = QmSheet::where('process_id',$process_id)->orderBy('version','desc')->get();
        $list=[];
        foreach ($temp_list as $key => $value) {
            $list[$value->id] = $value->name."-v ".$value->version;
        }
        return response()->json(['status'=>200,'message'=>"Success",'data'=>$list], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     
        $validator = Validator::make($request->all(), [
            'client_id' => 'required',
            'process_id' => 'required',
            'qmsheet_id' => 'required',
            'name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        if($validator->fails())
        {
            return redirect('audit_cycle/create')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
            $aucyc = new Auditcycle;
            $aucyc->start_date = date("Y-m-d", strtotime($request->start_date));
            $aucyc->end_date = date("Y-m-d", strtotime($request->end_date));
            $aucyc->client_id = $request->client_id;
            $aucyc->process_id = $request->process_id;
            $aucyc->qmsheet_id = $request->qmsheet_id;
            $aucyc->name = $request->name;

            $aucyc->save();
           
            return redirect('audit_cycle/')->with('success', 'Audit cycle created successfully'); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Auditcycle  $auditcycle
     * @return \Illuminate\Http\Response
     */
    public function show(Auditcycle $auditcycle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Auditcycle  $auditcycle
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Auditcycle::find(Crypt::decrypt($id));
        $all_clients = Client::all()->pluck('name','id');
        $all_process = Process::all()->pluck('name','id');
        $all_qmsheet = QmSheet::all()->pluck('name','id');
        return view('audit_cycle.edit',compact('data','all_clients','all_process','all_qmsheet'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Auditcycle  $auditcycle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required',
            'process_id' => 'required',
            'qmsheet_id' => 'required',
            'name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        if($validator->fails())
        {
            return redirect('audit_cycle/create')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
            $aucyc =  Auditcycle::find(Crypt::decrypt($id));
            $aucyc->start_date = date("Y-m-d", strtotime($request->start_date));
            $aucyc->end_date = date("Y-m-d", strtotime($request->end_date));
            $aucyc->client_id = $request->client_id;
            $aucyc->process_id = $request->process_id;
            $aucyc->qmsheet_id = $request->qmsheet_id;
            $aucyc->name = $request->name;
            $aucyc->save();
            return redirect('audit_cycle/')->with('success', 'Audit cycle updated successfully'); 
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Auditcycle  $auditcycle
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Auditcycle::find(Crypt::decrypt($id))->delete();
        return redirect('audit_cycle/')->with('success', 'Audit cycle deleted successfully.');
    }
}
