<?php

namespace App\Http\Controllers;

use App\Client;
use App\Imports\Rca2Import;
use App\Process;
use App\RcaTwo1;
use App\RcaTwo2;
use App\RcaTwo3;
use App\Rca2Mode;
use Auth;
use Crypt;
use Excel;
use Illuminate\Http\Request;
use Validator;

class Rca2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // dd($request->client_id);

        $client_list = Client::where('company_id',Auth::user()->company_id)->pluck('name','id');
        $process_list = Process::where('company_id',Auth::user()->company_id)->pluck('name','id');

        $data = Rca2Mode::where('client_id',$request->client_id)->where('process_id',$request->process_id)->with(['rcatwo1','rcatwo1.rcatwo2','rcatwo1.rcatwo2.rcatwo3'])->get();
        // dd($data);
        
        return view('rca2.list',compact('data','client_list','process_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $client_list = Client::where('company_id',Auth::user()->company_id)->pluck('name','id');
        $process_list = Process::where('company_id',Auth::user()->company_id)->pluck('name','id');
        return view('rca2.create',compact('client_list','process_list'));
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
            'company_id' => 'required',
            'rca2_data_file' => 'required'
        ]);

        if($validator->fails())
        {
            return redirect('rca2/create')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
            Excel::import(new Rca2Import(['company_id'=>Auth::user()->company_id,
                'client_id'=>$request->client_id,
                'process_id'=>$request->process_id]),$request->rca2_data_file);
        }
        return redirect('rca2')->with('success', 'Rca2 data uploaded and created successfully.');
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
    public function edit(Request $request)
    {
        switch ($request->data) {
            case "1":
                $data = Rca2Mode::find(Crypt::decrypt($request->id));
                $type = $request->data;
                return view('rca2.edit',compact('data','type'));
                break;
            case "2":
                $data = RcaTwo1::find(Crypt::decrypt($request->id));
                $type = $request->data;
                return view('rca2.edit',compact('data','type'));
                break;
            case "3":
                $data = RcaTwo2::find(Crypt::decrypt($request->id));
                $type = $request->data;
                return view('rca2.edit',compact('data','type'));
                break;
            case "4":
                $data = RcaTwo3::find(Crypt::decrypt($request->id));
                $type = $request->data;
                return view('rca2.edit',compact('data','type'));
                break;
        }
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
            'process_id' => 'required',
            'client_id' => 'required',
            'name' => 'required'
        ]);

        if($validator->fails())
        {
            return redirect('rca2/create')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
            switch ($request->type) {
                case "1":
                    $data = Rca2Mode::find(Crypt::decrypt($id));
                    $data->name = $request->name;
                    $data->save();
                    break;
                case "2":
                    $data = RcaTwo1::find(Crypt::decrypt($id));
                    $data->name = $request->name;
                    $data->save();
                    break;
                case "3":
                    $data = RcaTwo2::find(Crypt::decrypt($id));
                    $data->name = $request->name;
                    $data->save();
                    break;
                case "4":
                    $data = RcaTwo3::find(Crypt::decrypt($id));
                    $data->name = $request->name;
                    $data->save();
                    break;
            }
        }
        return redirect('rca2')->with('success', 'Rca data edited successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        switch ($request->data) {
            case "1":
                $data = Rca2Mode::find(Crypt::decrypt($request->id));
                $rca1_data = RcaTwo1::where('mode2_id',$data->id)->get();
                $data->delete();
                foreach ($rca1_data as $key => $value) {
                    $rca2_data = RcaTwo2::where('rcatwo1_id',$value->id)->get();
                    $value->delete();
                    foreach ($rca2_data as $key => $value) {
                        $rca3_data = RcaTwo3::where('rcatwo2_id',$value->id)->get();
                        $value->delete();
                        foreach ($rca3_data as $key => $value) {
                            $value->delete();
                        }

                    }
                }
                break;
            case "2":
                $rca1_data = RcaTwo1::find(Crypt::decrypt($request->id));
                $rca2_data = RcaTwo2::where('rcatwo1_id',$rca1_data->id)->get();
                $rca1_data->delete();
                foreach ($rca2_data as $key => $value) {
                    $rca3_data = RcaTwo3::where('rcatwo2_id',$value->id)->get();
                    $value->delete();
                    foreach ($rca3_data as $key => $value) {
                        $value->delete();
                    }

                }
                break;
            case "3":
                $rca2_data = RcaTwo2::find(Crypt::decrypt($request->id));
                $rca3_data = RcaTwo3::where('rcatwo2_id',$rca2_data->id)->get();
                $rca2_data->delete();
                foreach ($rca3_data as $key => $value) {
                    $value->delete();
                }
                break;
            case "4":
                $rca3_data = RcaTwo3::find(Crypt::decrypt($request->id));
                $rca3_data->delete();
                break;
        }
        return redirect('rca2')->with('success', 'Rca data deleted successfully.');
    }

    public function get_rcatwo1_by_rca_mode2_id($rca_mode2_id)
    {
        $data = RcaTwo1::where('mode2_id',$rca_mode2_id)->pluck('name','id');
        return response()->json(['status'=>200,'data'=>$data], 200);   
    }
    public function get_rcatwo2_by_rcatwo1_id($rcatwo1_id)
    {
        $data = RcaTwo2::where('rcatwo1_id',$rcatwo1_id)->pluck('name','id');
        return response()->json(['status'=>200,'data'=>$data], 200);   
    }
    public function get_rcatwo3_by_rcatwo2_id($rcatwo2_id)
    {
        $data = RcaTwo3::where('rcatwo2_id',$rcatwo2_id)->pluck('name','id');
        return response()->json(['status'=>200,'data'=>$data], 200);   
    }

    public function custom_form(Request $request)
    {
        switch ($request->data) {
            case "1":
                $id = Crypt::decrypt($request->id);
                $type = $request->data;
                break;
            
            case "2":
                $id = Crypt::decrypt($request->id);
                $type = $request->data;
                break;
        }
        return view('rca2.custom_create', compact('id','type'));
    }
    public function custom_add(Request $request)
    {
        switch ($request->type) {
            case "1":
                foreach ($request->rca as $key => $value) {
                    $rca2 = new RcaTwo2;
                    $rca2->rcatwo1_id = Crypt::decrypt($request->rca_id);
                    $rca2->name = $value['name'];
                    $rca2->save();
                }
                break;
            
            case "2":
                foreach ($request->rca as $key => $value) {
                    $rca3 = new RcaTwo3;
                    $rca3->rcatwo2_id = Crypt::decrypt($request->rca_id);
                    $rca3->name = $value['name'];
                    $rca3->save();
                }
                break;
        }
        return redirect('rca2')->with('success', 'Rca data created successfully.');

    }
}
