<?php

namespace App\Http\Controllers;

use App\TypeBScoringOption;
use Auth;
use Crypt;
use Illuminate\Http\Request;
use Validator;

class TypeBScoringController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = TypeBScoringOption::where('company_id',Auth::user()->company_id)->get();
        return view('type_b_scoring_option.list',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('type_b_scoring_option.create');
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
            return redirect('type_b_scoring_option/create')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
            $new_rc = new TypeBScoringOption;
            $new_rc->fill($request->all());
            $new_rc->save();
        }
        return redirect('type_b_scoring_option')->with('success', 'Scoring option created successfully'); 
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
        $data = TypeBScoringOption::find(Crypt::decrypt($id));
        return view('type_b_scoring_option.edit',compact('data'));
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
            return redirect('type_b_scoring_option/create')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
            $new_rc =  TypeBScoringOption::find(Crypt::decrypt($id));
            $new_rc->fill($request->all());
            $new_rc->save();
        }
        return redirect('type_b_scoring_option')->with('success', 'Scoring option updated successfully'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        TypeBScoringOption::find(Crypt::decrypt($id))->delete();
        return redirect('type_b_scoring_option')->with('success', 'TypeBScoringOption deleted successfully.');    
    }
}
