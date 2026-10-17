<?php

namespace App\Http\Controllers;
ini_set('memory_limit', '-1');
use App\Cluster;
use App\Circle;
use Auth;
use Crypt;
use Validator;
use Illuminate\Http\Request;

class ClusterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Cluster::with(['circle'])->get();
        return view('cluster.list',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cluster/create');
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
            'name' => 'required',
            'circle' => 'required'
        ]);

        if($validator->fails())
        {
            return redirect('cluster/create')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
            $cluster = new Cluster;
            $cluster->name = $request->name;
            $cluster->detail = $request->detail;
            $cluster->save();
            if($cluster->id)
            {
                foreach ($request->circle as $key => $value) {
                    if($value['name']){
                        $circle = new Circle;
                        $circle->cluster_id = $cluster->id;
                        $circle->name = $value['name'];
                        $circle->detail = $value['detail'];
                        $circle->save();
                    }
                }
            }
        }
        return redirect('cluster')->with('success', 'Cluster with Circle created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cluster  $cluster
     * @return \Illuminate\Http\Response
     */
    public function show(Cluster $cluster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cluster  $cluster
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Cluster::with('circle')->find(Crypt::decrypt($id));
        return view('cluster.edit',compact('data'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cluster  $cluster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         $validator = Validator::make($request->all(), [
            'name' => 'required',
            'circle' => 'required'
        ]);

        if($validator->fails())
        {
            return redirect('cluster/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
            $cluster = Cluster::find(Crypt::decrypt($id));
            $cluster->name = $request->name;
            $cluster->detail = $request->detail;
            $cluster->save();
            foreach ($request->circle as $key => $value) {
                if($value['name']){
                    if($value['row']){
                        $circle = Circle::find($value['row']);
                        $circle->cluster_id = $cluster->id;
                        $circle->name = $value['name'];
                        $circle->detail = $value['detail'];
                        $circle->save();    
                    }else{
                        $circle = new Circle;
                        $circle->cluster_id = $cluster->id;
                        $circle->name = $value['name'];
                        $circle->detail = $value['detail'];
                        $circle->save();
                    }  
                }  
            }
        }
        return redirect('cluster')->with('success', 'Cluster with Circle updated successfully'); 

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cluster  $cluster
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Cluster::find(Crypt::decrypt($id))->delete();
        Circle::where('cluster_id',Crypt::decrypt($id))->delete();
        return redirect('cluster')->with('success', 'Cluster with Circle deleted successfully.');
    }
    public function delete_circle_by_id($circle_id)
    {
        Circle::find($circle_id)->delete();    
    }
}
