<?php

namespace App\Http\Controllers;

use App\Client;
use App\Imports\RcaImport;
use App\Process;
use App\Rca1;
use App\Rca2;
use App\Rca3;
use App\RcaMode;
use App\RcaTwo1;
use App\RcaTwo2;
use App\RcaTwo3;
use Auth;
use Crypt;
use Excel;
use Illuminate\Http\Request;
use Validator;

class RcaController extends Controller
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

        $data = RcaMode::where('client_id',$request->client_id)->where('process_id',$request->process_id)->with(['rca1','rca1.rca2','rca1.rca2.rca3'])->get();
        // dd($data);
        
        return view('rca.list',compact('data','client_list','process_list'));
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
        return view('rca.create',compact('client_list','process_list'));
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
            'rca_data_file' => 'required'
        ]);

        if($validator->fails())
        {
            return redirect('rca/create')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
            Excel::import(new RcaImport(['company_id'=>Auth::user()->company_id,
                                         'client_id'=>$request->client_id,
                                         'process_id'=>$request->process_id]),$request->rca_data_file);
        }
        return redirect('rca')->with('success', 'Rca data uploaded and created successfully.');
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
        // dd($request->data);
        switch ($request->data) {
            case "1":
                $data = RcaMode::find(Crypt::decrypt($request->id));
                $type = $request->data;
                return view('rca.edit',compact('data','type'));
                break;
            case "2":
                $data = Rca1::find(Crypt::decrypt($request->id));
                $type = $request->data;
                return view('rca.edit',compact('data','type'));
                break;
            case "3":
                $data = Rca2::find(Crypt::decrypt($request->id));
                $type = $request->data;
                return view('rca.edit',compact('data','type'));
                break;
            case "4":
                $data = Rca3::find(Crypt::decrypt($request->id));
                $type = $request->data;
                return view('rca.edit',compact('data','type'));
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
            return redirect('rca/create')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
            switch ($request->type) {
                case "1":
                    $data = RcaMode::find(Crypt::decrypt($id));
                    $data->name = $request->name;
                    $data->save();
                    break;
                case "2":
                    $data = Rca1::find(Crypt::decrypt($id));
                    $data->name = $request->name;
                    $data->save();
                    break;
                case "3":
                    $data = Rca2::find(Crypt::decrypt($id));
                    $data->name = $request->name;
                    $data->save();
                    break;
                case "4":
                    $data = Rca3::find(Crypt::decrypt($id));
                    $data->name = $request->name;
                    $data->save();
                    break;
            }
        }
        return redirect('rca')->with('success', 'Rca data edited successfully.');
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
                $data = RcaMode::find(Crypt::decrypt($request->id));
                $rca1_data = Rca1::where('mode_id',$data->id)->get();
                $data->delete();
                foreach ($rca1_data as $key => $value) {
                    $rca2_data = Rca2::where('rca1_id',$value->id)->get();
                    $value->delete();
                    foreach ($rca2_data as $key => $value) {
                        $rca3_data = Rca3::where('rca2_id',$value->id)->get();
                        $value->delete();
                        foreach ($rca3_data as $key => $value) {
                            $value->delete();
                        }

                    }
                }
                break;
            case "2":
                $rca1_data = Rca1::find(Crypt::decrypt($request->id));
                $rca2_data = Rca2::where('rca1_id',$rca1_data->id)->get();
                $rca1_data->delete();
                foreach ($rca2_data as $key => $value) {
                    $rca3_data = Rca3::where('rca2_id',$value->id)->get();
                    $value->delete();
                    foreach ($rca3_data as $key => $value) {
                        $value->delete();
                    }

                }
                break;
            case "3":
                $rca2_data = Rca2::find(Crypt::decrypt($request->id));
                $rca3_data = Rca3::where('rca2_id',$rca2_data->id)->get();
                $rca2_data->delete();
                foreach ($rca3_data as $key => $value) {
                    $value->delete();
                }
                break;
            case "4":
                $rca3_data = Rca3::find(Crypt::decrypt($request->id));
                $rca3_data->delete();
                break;
        }
        return redirect('rca')->with('success', 'Rca data deleted successfully.');

    }

    public function get_rca1_by_rca_mode_id($rca_mode_id)
    {
        $data = Rca1::where('mode_id',$rca_mode_id)->pluck('name','id');
        return response()->json(['status'=>200,'data'=>$data], 200);   
    }
    public function get_rca2_by_rca1_id($rca1_id)
    {
        $data = Rca2::where('rca1_id',$rca1_id)->pluck('name','id');
        return response()->json(['status'=>200,'data'=>$data], 200);   
    }
    public function get_rca3_by_rca2_id($rca2_id)
    {
        $data = Rca3::where('rca2_id',$rca2_id)->pluck('name','id');
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
        return view('rca.custom_create', compact('id','type'));
    }

    public function custom_add(Request $request)
    {
        switch ($request->type) {
            case "1":
                foreach ($request->rca as $key => $value) {
                    $rca2 = new Rca2;
                    $rca2->rca1_id = Crypt::decrypt($request->rca_id);
                    $rca2->name = $value['name'];
                    $rca2->save();
                }
                break;
            
            case "2":
                foreach ($request->rca as $key => $value) {
                    $rca3 = new Rca3;
                    $rca3->rca2_id = Crypt::decrypt($request->rca_id);
                    $rca3->name = $value['name'];
                    $rca3->save();
                }
                break;
        }
        return redirect('rca')->with('success', 'Rca data created successfully.');

    }



    public function get_type_2_rca1_by_rca_mode_id($rca_mode_id)
    {
        $data = RcaTwo1::where('mode2_id',$rca_mode_id)->pluck('name','id');
        return response()->json(['status'=>200,'data'=>$data], 200);   
    }
    public function get_type_2_rca2_by_rca1_id($rca1_id)
    {
        $data = RcaTwo2::where('rcatwo1_id',$rca1_id)->pluck('name','id');
        return response()->json(['status'=>200,'data'=>$data], 200);   
    }
    public function get_type_2_rca3_by_rca2_id($rca2_id)
    {
        $data = RcaTwo3::where('rcatwo2_id',$rca2_id)->pluck('name','id');
        return response()->json(['status'=>200,'data'=>$data], 200);   
    }
}
