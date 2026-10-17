<?php

namespace App\Http\Controllers;

use App\Region;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Crypt;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Region::where('company_id',Auth::user()->company_id)->get();
        return view('region.list',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('region.create');
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
            'name' => 'required'
        ]);

        if($validator->fails())
        {
            return redirect('region/create')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
            $new_rc = new Region;
            $new_rc->fill($request->all());
            $new_rc->save();
        }
        return redirect('region')->with('success', 'Region created successfully');    
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
        $data = Region::find(Crypt::decrypt($id));
        return view('region.edit',compact('data'));
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
            'name' => 'required'
        ]);

        if($validator->fails())
        {
            return redirect('region/create')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
            $new_rc =  Region::find(Crypt::decrypt($id));
            $new_rc->fill($request->all());
            $new_rc->save();
        }
        return redirect('region')->with('success', 'Region edited successfully'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Region::find(Crypt::decrypt($id))->delete();
        return redirect('region')->with('success', 'Region deleted successfully.');    
    }
}
