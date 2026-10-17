<?php

namespace App\Http\Controllers;

use App\Client;
use App\Process;
use App\Reason;
use App\ReasonType;
use Auth;
use Carbon\Carbon;
use Crypt;
use Illuminate\Http\Request;
use Validator;

class ReasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        $data = ReasonType::where('company_id',Auth::user()->company_id)->with(['reasons','client','process'])->get();
        return view('reasons.list',compact('data'));
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
        return view('reasons.create',compact('client_list','process_list'));
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
            'name' => 'required',
            'client_id' => 'required',
            'process_id' => 'required',
            'reasons' => 'required'
        ]);

        if($validator->fails())
        {
            return redirect('reason/create')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
            $new_rc = new ReasonType;
            $new_rc->fill($request->all());
            $new_rc->save();

            if($new_rc->id)
            {
                foreach ($request->reasons as $key => $value) {
                    $new_rr = new Reason;
                    $new_rr->reason_type_id = $new_rc->id;
                    $new_rr->name = $value['reason'];
                    $new_rr->save();
                }
            }
        }
        return redirect('reason')->with('success', 'Reason type with reasons created successfully'); 
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
        $data = ReasonType::with('reasons')->find(Crypt::decrypt($id));
        $client_list = Client::where('company_id',Auth::user()->company_id)->pluck('name','id');
        $process_list = Process::where('company_id',Auth::user()->company_id)->pluck('name','id');
        return view('reasons.edit',compact('data','client_list','process_list'));
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
            'name' => 'required',
            'reasons' => 'required'
        ]);

        if($validator->fails())
        {
            return redirect('reason/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
            $new_rc = ReasonType::find(Crypt::decrypt($id));
            $new_rc->fill($request->all());
            $reason_type_id = $new_rc->id;
            $new_rc->save();

            
                //Reason::where('reason_type_id',$reason_type_id)->delete();
                foreach ($request->reasons as $key => $value) {
                    if($value['reason'])
                    {
                        if($value['row_id'])
                            $new_rr = Reason::find($value['row_id']);    
                        else
                        $new_rr = new Reason;

                        $new_rr->reason_type_id = $reason_type_id;
                        $new_rr->name = $value['reason'];
                        $new_rr->save();
                    }
                
            }
        }
        return redirect('reason')->with('success', 'Reason type with reasons updated successfully'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ReasonType::find(Crypt::decrypt($id))->delete();
        Reason::where('reason_type_id',Crypt::decrypt($id))->delete();
        return redirect('reason')->with('success', 'Reason deleted successfully.');    
    }

    public function delete_reason_by_id($reason_id)
    {
        Reason::find($reason_id)->delete();
        return redirect('reason')->with('success', 'Reason deleted successfully.');    
    }
    
}
