<?php

namespace App\Http\Controllers;

use App\Language;
use Auth;
use Crypt;
use Illuminate\Http\Request;
use Validator;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Language::where('company_id',Auth::user()->company_id)->get();
        return view('language.list',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('language.create');
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
            'name' => 'required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'
        ]);

        if($validator->fails())
        {
            return redirect('language/create')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
            $new_rc = new Language;
            $new_rc->fill($request->all());
            $new_rc->save();
        }
        return redirect('language')->with('success', 'Language created successfully'); 
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
        $data = Language::find(Crypt::decrypt($id));
        return view('language.edit',compact('data'));
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
            'name' => 'required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'
        ]);

        if($validator->fails())
        {
            return redirect('language/'.$id."/edit")
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
            $new_rc =  Language::find(Crypt::decrypt($id));
            $new_rc->fill($request->all());
            $new_rc->save();
        }
        return redirect('language')->with('success', 'Language edited successfully'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Language::find(Crypt::decrypt($id))->delete();
        return redirect('language')->with('success', 'Language deleted successfully.');    
    }
}
