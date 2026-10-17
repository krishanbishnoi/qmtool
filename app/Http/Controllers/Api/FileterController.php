<?php

namespace App\Http\Controllers\Api;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '0');
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use Illuminate\Support\Facades\Hash;
Use App\User;
use App\Audit;
use App\AuditParameterResult;
use App\AuditResult;
use App\Client;
use App\Exports\AgentQuartileExport;
use App\Partner;
use App\PartnerLocation;
use App\PartnersProcess;
use App\QmSheet;
use App\QmSheetParameter;
use App\QmSheetSubParameter;
use App\RawData;
use App\ReLabel;
use App\Reason;
use App\ReasonType;
use App\Auditcycle;
use App\ClientAdmin;
use Auth;
use Crypt;
use DB;
use Illuminate\Database\Eloquent\Builder;

use Maatwebsite\Excel\Facades\Excel;
use App\PartnersProcessSpoc;
use PDF;
use App\MonthTarget;
use App\Process;

class FileterController extends Controller
{
    public function get_partner_list_detail_dash(Request $request){
		$user_id = $request->user_id;
		$user = User::where('id',$user_id)->first();

		

		if($user->id == 42 || $user->id == 172 || $user->id == 198) {
			$client_list=Client::where('id',1)->orwhere('id',9)->get();
		} else {
			$client_list=array();
		}
		/*  echo "hii";
			dd(); */
			if($user->hasRole('partner-admin'))
			{   
				if(($user->id == 241 || $user->id == 242 || $user->id == 251 || $user->id == 246)) {
					$all_partners = Partner::select('name','id')->where('id',32)->get();
				}  
				else if($user->id == 248 || $user->id == 253) {
					$all_partners = Partner::select('name','id')->where('id',40)->get();
				}
				elseif($user->id == 139) {
					$all_partners = Partner::select('name','id')->where('id',38)->get();
				}
				elseif($user->id == 195) {
					$all_partners = Partner::select('name','id')->where('id',39)->get();
				}
				elseif($user->id == 254) {
					$all_partners = Partner::select('name','id')->where('id',43)->get();
				}
				elseif($user->id == 249 || $user->id == 250) {
					$all_partners = Partner::select('name','id')->where('id',41)->get();
				}
				elseif($user->id == 252 || $user->id == 269) {
					$all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',39)->orWhere('id',43)->orWhere('id',45)->get();
				}
				elseif($user->id == 270 || $user->id == 271) {
					$all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',45)->get();
				}
				elseif($user->id == 256 || $user->id == 255) {
					$all_partners = Partner::select('name','id')->where('id',44)->get();
				} else {

					$all_partners = Partner::where('id',$user->partner_admin_detail->id)->pluck('name','id');
				}  
			}elseif($user->hasRole('partner-training-head')||
					$user->hasRole('partner-operation-head'))
					
			{
				if(($user->id == 241 || $user->id == 242 || $user->id == 251 || $user->id == 246)) {
					$all_partners = Partner::select('name','id')->where('id',32)->get();
				}  
				else if($user->id == 248 || $user->id == 253) {
					$all_partners = Partner::select('name','id')->where('id',40)->get();
				}
				elseif($user->id == 139) {
					$all_partners = Partner::select('name','id')->where('id',38)->get();
				}
				elseif($user->id == 195) {
					$all_partners = Partner::select('name','id')->where('id',39)->get();
				}
				elseif($user->id == 254) {
					$all_partners = Partner::select('name','id')->where('id',43)->get();
				}
				elseif($user->id == 249 || $user->id == 250) {
					$all_partners = Partner::select('name','id')->where('id',41)->get();
				}
				elseif($user->id == 252 || $user->id == 269) {
					$all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',39)->orWhere('id',43)->orWhere('id',45)->get();
				}
				elseif($user->id == 270 || $user->id == 271) {
					$all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',45)->get();
				}
				elseif($user->id == 256 || $user->id == 255) {
					$all_partners = Partner::select('name','id')->where('id',44)->get();
				} else {
				$all_partners = Partner::where('id',$user->spoc_detail->partner_id)->pluck('name','id'); 
				} 
			}elseif( $user->hasRole('partner-quality-head') ){



				$partner = PartnersProcessSpoc::where('user_id',$user->id)->get();  

				$partnerid =  $partner[0]['partner_id'];    
				if ($user->id == 267) {
				
				$all_partners = Partner::select('name','id')->where('id',45)->get();
				} else {

					$all_partners = Partner::where('id',$partnerid)->get(); 
				}

			

			}elseif($user->hasRole('client')){
				if(($user->id == 241 || $user->id == 242 || $user->id == 251 || $user->id == 246)) {
					$all_partners = Partner::select('name','id')->where('id',32)->get();
				}  
				else if($user->id == 248 || $user->id == 253) {
					$all_partners = Partner::select('name','id')->where('id',40)->get();
				}
				elseif($user->id == 139) {
					$all_partners = Partner::select('name','id')->where('id',38)->get();
				}
				elseif($user->id == 195) {
					$all_partners = Partner::select('name','id')->where('id',39)->get();
				}
				elseif($user->id == 254) {
					$all_partners = Partner::select('name','id')->where('id',43)->get();
				}
				elseif($user->id == 249 || $user->id == 250) {
					$all_partners = Partner::select('name','id')->where('id',41)->get();
				}
				elseif($user->id == 252 || $user->id == 269) {
					$all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',39)->orWhere('id',43)->orWhere('id',45)->get();
				}
				elseif($user->id == 270 || $user->id == 271) {
					$all_partners = Partner::select('name','id')->where('id',38)->orWhere('id',45)->get();
				}
				elseif($user->id == 256 || $user->id == 255) {
					$all_partners = Partner::select('name','id')->where('id',44)->get();
				}
				elseif($user->id == 42 || $user->id == 172 || $user->id == 198) {
					$client_id=9;
					$all_partners = Partner::select('name','id')->where('client_id',9)->get();
				}
				else {
					$all_partners = Partner::select('name','id')->where('client_id',$user->client_detail->client_id)->get();
				}       
			}

			if($user->id == 42 || $user->id == 172 || $user->id == 198 || $user->id == 267 ) {
				$client_id=9;
				$all_partners = Partner::select('name','id')->where('client_id',9)->get();
			}
			if ($user->id == 267) {
				
				$all_partners = Partner::select('name','id')->where('id',45)->get();
			} 

			//print_r($all_partners);
			return response()->json(['status'=>200,'message'=>"Success",'data'=>$all_partners], 200);
	}
	
	public function get_location_lob_process_of_partner(Request $request){

		$user_id = $request->user_id;
		$user = User::where('id',$user_id)->first();
		$partner_id = $request->partner_id;

		// get location start
		if($partner_id == 'all'){
            if($user->id == 42 || $user->id == 172 || $user->id == 198) {
                $all_partners = Partner::where('client_id',9)->get();
            } else if($user->id == 85){
                $client_id = 1;
                $all_partners = Partner::where('client_id',$client_id)->get();
            }else {
                $all_partners = Partner::where('client_id',$user->client_detail->client_id)->get();
            }
            
            
            foreach ($all_partners as $p) {
                $temp_all_locations = PartnerLocation::where('partner_id',$p->id)->with('location_detail')->get();                
                foreach ($temp_all_locations as $key => $value) {
                	if(($user->id == 241 || $user->id == 242 || $user->id == 251 || $user->id == 246)) {
                    	if($value->location_id == 36) {
                        	$all_location[$value->location_id] = $value->location_detail->name;
                    	}	
                    } 
                    elseif($user->id == 248 || $user->id == 253) {
                        if($value->location_id == 44) {
                            $all_location[$value->location_id] = $value->location_detail->name;
                        }   
                    }
                    elseif($user->id == 139) {
                        if($value->location_id == 14) {
                            $all_location[$value->location_id] = $value->location_detail->name;
                        }   
                    }
                    elseif($user->id == 195) {
                        if($value->location_id == 2) {
                            $all_location[$value->location_id] = $value->location_detail->name;
                        }   
                    }
                    elseif($user->id == 254) {
                        if($value->location_id == 2) {
                            $all_location[$value->location_id] = $value->location_detail->name;
                        }   
                    }
                    elseif($user->id == 249 || $user->id == 250) {
                        if($value->location_id == 44) {
                            $all_location[$value->location_id] = $value->location_detail->name;
                        }  
                    }
                    elseif($user->id == 252 || $user->id == 269) {
                        if($value->location_id == 2 || $value->location_id == 14 || $value->location_id == 20) {
                            $all_location[$value->location_id] = $value->location_detail->name;
                        } 
                    }
                    elseif($user->id == 256 || $user->id == 255) {
                         if($value->location_id == 14) {
                            $all_location[$value->location_id] = $value->location_detail->name;
                        }   
                    }
                    else {
                		$all_location[$value->location_id] = $value->location_detail->name;
                	}
                }
            }

        }else {
            $temp_all_locations = PartnerLocation::where('partner_id',$partner_id)->with('location_detail')->get();
            $all_location = [];
            
            foreach ($temp_all_locations as $key => $value) {
            	if(($user->id == 241 || $user->id == 242 || $user->id == 251 || $user->id == 246)) {
                	if($value->location_id == 36) {
                $all_location[$value->location_id] = $value->location_detail->name;
            	}} 
                elseif($user->id == 248 || $user->id == 253) {
                    if($value->location_id == 44) {
                        $all_location[$value->location_id] = $value->location_detail->name;
                    }
                } 
                 elseif($user->id == 139) {
                    if($value->location_id == 14) {
                        $all_location[$value->location_id] = $value->location_detail->name;
                    }
                 }
                 elseif($user->id == 195) {
                    if($value->location_id == 2) {
                        $all_location[$value->location_id] = $value->location_detail->name;
                    }
                 }
                 elseif($user->id == 254) {
                    if($value->location_id == 2) {
                        $all_location[$value->location_id] = $value->location_detail->name;
                    }
                 }
                elseif($user->id == 249 || $user->id == 250) {
                    if($value->location_id == 44) {
                        $all_location[$value->location_id] = $value->location_detail->name;
                    }
                }
                elseif($user->id == 252 || $user->id == 269) {
                    if($value->location_id == 2 || $value->location_id == 14 || $value->location_id == 20) {
                        $all_location[$value->location_id] = $value->location_detail->name;
                    } 
                }
                elseif($user->id == 256 || $user->id == 255) {
                         if($value->location_id == 14) {
                            $all_location[$value->location_id] = $value->location_detail->name;
                        }   
                    }
                else {
            		$all_location[$value->location_id] = $value->location_detail->name;
            	}
            }
            
		}
		// get location ends

		if($partner_id == 'all'){
            if($user->id == 42 || $user->id == 172 || $user->id == 198){
                $all_partners = Partner::where('client_id',9)->get();  
            } else if($user->id == 85){
                $client_id = 1;
                $all_partners = Partner::where('client_id',$client_id)->get();  
            } else {
                $all_partners = Partner::where('client_id',$user->client_detail->client_id)->get();  
            }
            
            foreach($all_partners as $p) {

                $all_process = PartnersProcess::where('partner_id',$p->id)->with('process')->get();
                foreach ($all_process as $key => $value) {
                	if($user->id == 241 || $user->id == 242 || $user->id == 251 || $user->id == 246) {
            		if($value->process_id == 25 || $value->process_id == 26 || $value->process_id == 30) { 
            			$temp_all_process[$value->process_id] = $value->process->name;
            		}}
            		elseif($user->id == 248 || $user->id == 253) {
	        			if($value->process_id == 23) {
	        				$temp_all_process[$value->process_id] = $value->process->name;
	        			}
	            	}
	            	elseif($user->id == 249 || $user->id == 250) {
	        			if($value->process_id == 31) {
	        				$temp_all_process[$value->process_id] = $value->process->name;
	        			}
	            	}
	            	elseif($user->id == 252 || $user->id == 139 || $user->id == 269 || $user->id == 270 || $user->id == 271) {
	        			if($value->process_id == 21 || $value->process_id == 22) {
	        				$temp_all_process[$value->process_id] = $value->process->name;
	        			}
	            	}
                    elseif($user->id == 195) {
                        if($value->process_id == 22) {
                            $temp_all_process[$value->process_id] = $value->process->name;
                        }
                    }
                    elseif($user->id == 254) {
                        if($value->process_id == 22) {
                            $temp_all_process[$value->process_id] = $value->process->name;
                        }
                    }
	            	elseif($user->id == 256 || $user->id == 255) {
	        			if($value->process_id == 32) {
	        				$temp_all_process[$value->process_id] = $value->process->name;
	        			}
	            	}
            		 else {
            			$temp_all_process[$value->process_id] = $value->process->name;
            		}
                    
                }
            }

           
        } else {
            $temp_all_processs = PartnersProcess::where('partner_id',$partner_id)->with('process')->get();        
			$temp_all_process = [];
            foreach($temp_all_processs as $key => $value) {
            	if($user->id == 241 || $user->id == 242 || $user->id == 251 || $user->id == 246) {
            		if($value->process_id == 25 || $value->process_id == 26 || $value->process_id == 30) { 
            			$temp_all_process[$value->process_id] = $value->process->name;
            		} 
            	}
            	elseif($user->id == 248 || $user->id == 253) {
        			if($value->process_id == 23) {
        				$temp_all_process[$value->process_id] = $value->process->name;
        			}
            	}
            	elseif($user->id == 249 || $user->id == 250) {
        			if($value->process_id == 31) {
        				$temp_all_process[$value->process_id] = $value->process->name;
        			}
            	}
            	elseif($user->id == 252 || $user->id == 139 || $user->id == 269 || $user->id == 270 || $user->id == 271) {
        			if($value->process_id == 21 || $value->process_id == 22) {
        				$temp_all_process[$value->process_id] = $value->process->name;
        			}
            	}
                elseif($user->id == 195) {
                    if($value->process_id == 22) {
						$temp_all_process[$value->process_id] = $value->process->name;
                    }
                }
                elseif($user->id == 254) {
                    if($value->process_id == 22) {
                        $temp_all_process[$value->process_id] = $value->process->name;
                    }
                }
            	elseif($user->id == 256 || $user->id == 255) {
        			if($value->process_id == 32) {
        				$temp_all_process[$value->process_id] = $value->process->name;
        			}
            	}
            	else {
            		$temp_all_process[$value->process_id] = $value->process->name;
            	} 
                
            }
        
		}
		// get process ends

		// get lob start 

		if($partner_id == 'all'){
        	if(($user->id == 241 || $user->id == 242 || $user->id == 251 || $user->id == 246)) {  
        		$temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',32)->get()->toArray();
        	}
            elseif($user->id == 248 || $user->id == 253) {
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',40)->get()->toArray();

            }
            elseif ($user->id == 139) {
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',38)->get()->toArray();
            }
            elseif ($user->id == 195) {
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',39)->get()->toArray();
            }
            elseif ($user->id == 254) {
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',43)->get()->toArray();
            }
            elseif($user->id == 249 || $user->id == 250) {
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',41)->get()->toArray();
            }
            elseif($user->id == 252 || $user->id == 269) {
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',38)->orWhere('partner_id',39)->orWhere('partner_id',43)->orWhere('partner_id',45)->get()->toArray();
            }
            elseif($user->id == 270 || $user->id == 271) {
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',38)->orWhere('partner_id',45)->get()->toArray();
            }
            elseif($user->id == 256 || $user->id == 255) { 
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',44)->get()->toArray();
            }
            else if($user->id = 42 || $user->id == 172 || $user->id == 198){
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('client_id',9)->get()->toArray();
            }
            else {
        		$temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('client_id',$user->client_detail->client_id)->get()->toArray();
        	}
            
        } else {
        	if(($user->id == 241 || $user->id == 242 || $user->id == 251 || $user->id == 246)) { 
        		$temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',32)->get()->toArray();
        	} 
            elseif($user->id == 248 || $user->id == 253) {
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',40)->get()->toArray();
            }
            elseif ($user->id == 139) {
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',38)->get()->toArray();
            }
            elseif ($user->id == 195) {
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',39)->get()->toArray();
            }
            elseif ($user->id == 254) {
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',43)->get()->toArray();
            }
            elseif($user->id == 249 || $user->id == 250) {
                $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',41)->get()->toArray();
            } elseif($user->id == 252 || $user->id == 269) {
                 $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',38)->orWhere('partner_id',39)->orWhere('partner_id',43)->orWhere('partner_id',45)->get()->toArray();
            }
            elseif($user->id == 270 || $user->id == 271) {
                 $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',38)->orWhere('partner_id',45)->get()->toArray();
            }
            elseif($user->id == 256 || $user->id == 255) {
                 $temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',44)->get()->toArray();
            }
            else {
        		$temp_all_lob = RawData::select(DB::raw("distinct lob as lob"))->where('partner_id',$partner_id)->get()->toArray();
        	}

           
		}
		
		$final_list  = array();
            
		foreach($temp_all_process as $key => $value){
			$new_arr = array();

			$new_arr['id'] = $key;
			$new_arr['name'] = $value;
			
			$final_list[] = $new_arr;
		}

		$final_list2  = array();
            
		foreach($all_location as $key => $value){
			$new_arr2 = array();

			$new_arr2['id'] = $key;
			$new_arr2['name'] = $value;
			
			$final_list2[] = $new_arr2;
		}

		$finalData['all_lob'] = $temp_all_lob;
		$finalData['process'] = $final_list;
		$finalData['location'] = $final_list2;
        
        return response()->json(['status'=>200,'message'=>"Success",'data'=>$finalData], 200);    
	}

	public function get_process_cycle(Request $request){
		
		$validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'process_id' => 'required',
		]);
		
		/* echo $request->email;
		dd(); */
        if($validator->fails()) {
            $data=array('status'=>0,'message'=>'Validation Errors','data' => $validator->errors());
            return response(json_encode($data), 200);
		}
		else {
			$user_id = $request->user_id;
			$user = User::where('id',$user_id)->first();
			$process_id = $request->process_id;
			
	
			if($user->id == 253 || $user->id == 255 || $user->id == 250 || $user->id == 246 || $user->id == 139 || $user->id == 195 || $user->id == 254 || $user->id == 42 || $user->id == 172 || $user->id == 198 || $user->id == 267 ) {
				$client_id=9;
			} else {
				//$client_id=1;
				if($user->id == 85){
					$client_id = 1;
				} else {
					$client_id = $user->client_detail->client_id;
				}
				
			}
			
			$audit_cyle_data = Auditcycle::where('client_id',$client_id)->where('process_id',$process_id)->orderby('start_date','desc')->get();
	
			return response()->json(['status'=>200,'message'=>"Success",'data'=>$audit_cyle_data], 200);    
		}
		
	}

    public function login(Request $request)
    {
		/* echo "hii";
		die; */
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
		]);
		
		/* echo $request->email;
		dd(); */
        if($validator->fails()) {
            $data=array('status'=>0,'message'=>'Validation Errors','data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
			
            $getUser=User::select('id','email','name','password','email_verified_at')->where(['email'=>$request->email])->first();
			
			if($getUser) {
                if(!Hash::check(trim($request->password),$getUser->password)) {
                    $data=array('status'=>0,'message'=>'Password not matched','data'=> array());
                } else { 
					/* echo "hii";
					dd(); */
                        //$getUser->last_login=date('Y-m-d h:i:s');
                        $getUser->remember_token=Hash::make($request->email);
                        $getUser->save();
                        $finalData=array('auth_key'=>$getUser->remember_token);
                       // $url=asset('images/profile_pic');                   
                        $data=array('status'=>1,'name'=>$getUser->name,'auth_key'=> $getUser->remember_token,'user_id'=>$getUser->id,
                        'email'=>$getUser->email,'message'=>'Login Successfully');
                                    
                }  
            } else {
                $data=array('status'=>0,'message'=>"You don't have an account.Please signup.",'data'=> array());
            }                       
            return response(json_encode($data),200);
        }        
    }

}
