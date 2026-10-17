<?php

namespace App\Http\Controllers;

use App\Client;
use App\Process;
use App\RcaType;
use Auth;
use Crypt;
use Illuminate\Http\Request;
use Validator;

class RcaTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = RcaType::where('company_id',Auth::user()->company_id)->with(['client','process'])->get();
        return view('rca_type.list',compact('data'));
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
        return view('rca_type.create',compact('client_list','process_list'));
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
            'name' => 'required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
            'client_id'=> 'required',
            'process_id'=> 'required',
        ]);

        if($validator->fails())
        {
            return redirect('process/create')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
            $new_rc = new RcaType;
            $new_rc->fill($request->all());
            $new_rc->save();
        }
        return redirect('rca_type')->with('success', 'Type created successfully');    
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
        $data = RcaType::find(Crypt::decrypt($id));
        $client_list = Client::where('company_id',Auth::user()->company_id)->pluck('name','id');
        $process_list = Process::where('company_id',Auth::user()->company_id)->pluck('name','id');
        return view('rca_type.edit',compact('data','client_list','process_list'));
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
            'name' => 'required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
            'client_id'=> 'required',
            'process_id'=> 'required'
        ]);
        
        if($validator->fails())
        {
            return redirect('rca_type/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
            $new_rc =  RcaType::find(Crypt::decrypt($id));
            $new_rc->fill($request->all());
            $new_rc->save();
        }

        return redirect('rca_type')->with('success', 'Type edited successfully'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        RcaType::find(Crypt::decrypt($id))->delete();
        return redirect('rca_type')->with('success', 'Type deleted successfully.');    
    }
}
