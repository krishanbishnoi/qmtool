<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PartnerMapping;
use App\LocationMapping;
use App\ProcessMapping;
use App\ClusterClientMapping;
use App\LobMapping;
use App\Holiday;
use App\User;
use Crypt;
use Auth;
class ClusterAccessController extends Controller
{
    public function get_user_list(){
        $data = User::where('parent_client',"!=",0)->get();
        
        return view('role_mapping.list',compact('data'));
    }
    public function get_user($id){
        
        $user_id = Crypt::decrypt($id);
        $clients = User::select('clients.id','clients.name')
        ->join('clients','users.parent_client','clients.id')->
        where('users.id',$user_id)->get();

        //$clients = ClusterClientMapping::select('clients.id','clients.name')->join('clients','cluster_client_mappings.Client_id','clients.id')->where('cluster_client_mappings.user_id',$user_id)->get();
        $process = ProcessMapping::select('processes.id','processes.name')->join('processes','process_mappings.process_id','processes.id')->where('process_mappings.client_id',$user_id)->get();
        $partner = PartnerMapping::select('partners.id','partners.name')->join('partners','partner_mappings.partner_id','partners.id')->where('partner_mappings.client_id',$user_id)->get();
        $location = LocationMapping::select('regions.id','regions.name')->join('regions','location_mappings.location_id','regions.id')->where('location_mappings.client_id',$user_id)->get();
        //$lob = LobMapping::where('user_id',$user_id)->get();

        return view('role_mapping.details',compact('clients','process','partner','location','lob'));
    }
}
