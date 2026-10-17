<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use Crypt;
use App\Permission;
use Validator;
use Auth;

class RoleController extends Controller
{

	public function list_permissions()
	{
		$data = Permission::all();
		return view('acl.list_permission',compact('data'));
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Role::where('id','>',1)->where('company_id',0)->orWhere('company_id',Auth::user()->company_id)->get();
        return view('acl.role.list',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('acl.role.create');
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
            'name' => 'required|unique:roles'
        ]);

        if ($validator->fails()) {
            return redirect('role/create')
                        ->withErrors($validator)
                        ->withInput();
        }
        $createPost = new Role();
        $createPost->name         = $request->name;
        $createPost->display_name = $request->display_name;
        $createPost->description  = $request->description;
        $createPost->save();
        return redirect('role')->with('success', 'Role Created Successfully');
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
        $data = Role::findOrFail(Crypt::decrypt($id));
        $permissions = Permission::pluck('display_name','id')->all();
        return view('acl.role.edit',['data'=>$data,'permissions'=>$permissions]);
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
        $record = Role::find(Crypt::decrypt($id));
            $record->name = $request->name;
            $record->display_name = $request->display_name;
            $record->description = $request->description;
            
            $record->save();

            return redirect('role')->with('success', 'Roles Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dd = Role::where('id',Crypt::decrypt($id))->forceDelete();
        return redirect('role')->with('success', 'Record Deleted Successfully');
    }
    public function assign_permission(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'role_id' => 'required',
            'permission_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('role/'.Crypt::encrypt($request->role_id).'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }
        $role = Role::findOrFail($request->role_id);
        $permission = Permission::findOrFail($request->permission_id);
        
        if (!$role->perms()->get()->contains('id', $permission->id)) {
            $role->attachPermission($permission);
        }
        

        return redirect('role/'.Crypt::encrypt($request->role_id).'/edit')->with('success', 'Permission Assigned Successfully');
    }
    public function detach_permission($role_id,$perm_id)
    {
        //dd($perm_id);
        $role = Role::findOrFail(Crypt::decrypt($role_id));
        $role->perms()->detach(Crypt::decrypt($perm_id));
        return redirect('role/'.$role_id.'/edit')->with('success', 'Permission Detached Successfully');
    }
}
