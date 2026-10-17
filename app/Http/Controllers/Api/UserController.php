<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;
Use App\User;
class UserController extends Controller
{
    public function index(){
        echo "hiiii";
        
    }

    public function login(Request $request)
    {
		// dd('here');
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
		]);
		
		/* echo 'hii';
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
                        'email'=>$getUser->email,'image'=>$getUser->avatar,'message'=>'Login Successfully','client'=>'qa_views');
                                    
                }  
            } else {
                $data=array('status'=>0,'message'=>"You don't have an account.Please signup.",'data'=> array());
            }                       
            return response(json_encode($data),200);
        }        
    }
    public function validateUser(Request $request) {
    	$validator = Validator::make($request->all(), [            
            'email' => 'required|email|unique:users',
            'mobile_no' =>'required|numeric|digits:10|unique:users'      
        ]);
        if($validator->fails()) {
            $data=array('status'=>0,'message'=> 'Validation Errors' ,'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
        	$explodeEmail=explode("@",$request->email);
        	$getUser=User::where('email',$request->email)->orWhere('mobile_no',$request->mobile_no)->first();
        	if(!$getUser) {
        		$userCreate=array();
        		$userCreate['email']=$request->email;
        		$userCreate['is_email_verified']=0;
        		$userCreate['is_mobile_verified']=0;
        		$userCreate['mobile_no']=$request->mobile_no;
                $randomString = str_random(25);
                $userCreate['code']=$randomString;
        		$userCreate['remember_token']=Hash::make($request->email);
        		$checkEmp=CosecUserRecord::where('email',$request->email)->first();
        		if($explodeEmail[1] == "qdegrees.com") {        			
        			if($checkEmp) {
        				$userCreate['user_type']=5;
        				$userCreate['emp_id']=$checkEmp->emp_id;
        			} else {
        				$data=array('status'=>0,'message'=> 'Invalid official email id.' ,'data' => array());
                    	return response(json_encode($data), 200);
        			}
        		} else {
        			$userCreate['user_type']=6;
        		}
        		$emailCode=rand(1000,9999);
        		$mobileCode=rand(1000,9999);
        		$userCreate['form_submit_level']=0;
        		$userCreate['status']=0;
        		$userCreate['created_by_usertype']="self_enroll";
        		$userCreate['otp_code_email']=$emailCode;
        		$userCreate['otp_code_mobile']=$mobileCode;
        		$user=User::create($userCreate);
        		if($user->user_type == 5) {
        			$addBasicInfo=UserBasicInformation::create(['user_id'=>$user->id,'full_name'=>$checkEmp->name,'gender'=>$checkEmp->gender,'dob'=>$checkEmp->dob,'marital_status'=>$checkEmp->marital_status]);
        		}
        		// Send Mobile OTP 
        		$msg1=" OTP code from RetailQ.com.";
                $m=$this->sendSms($request->mobile_no,$mobileCode,$msg1);
        		$getUserData=User::find($user->id)->toArray();
                $getUserData['url']=url('/verifyUserEmail').'/'.$getUserData['code'];
        		$er_msg="";
        		try{
					Mail::send('email_template.email_verify',['user' => $getUserData ], function($message) use($user)
							{
							    $message->to($user->email)->subject('OTP from RetailQ.com');  
							});												
				} 
				catch(\Exception $e){
		            $er_msg = $e->getMessage();
				}				
		        $data=array('status'=>1,'message'=> 'User Account Successfully created.' ,'data' => $user);
            	return response(json_encode($data), 200);		        
        	} else {
        		if($getUser->form_submit_level == 1) {
        			$UserData=User::with('basic_info')->find($getUser->id);
        		} else {
        			if($getUser->user_type == 5) {
        				$UserData=User::with('basic_info')->find($getUser->id);
        			} else {
        				$UserData=User::where('email',$request->email)->orWhere('mobile_no',$request->mobile_no)->first();
        			}        			
        		}
        		$data=array('status'=>1,'message'=> 'Already have an User Account associated with these details.' ,'data' => $UserData);
            	return response(json_encode($data), 200);
        	}        	
        }
	}
	
	public function reset_password(Request $request)
    {
		/* echo "hii";
		die; */
        $validator = Validator::make($request->all(), [
            'id' => 'required',
			'password' => 'required',
			'password_new' => 'required|min:8',
		]);
		
		/* echo $request->email;
		dd(); */
        if($validator->fails()) {
            $data=array('status'=>0,'message'=>'Validation Errors','data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
			
            $getUser=User::select('id','email','name','password','email_verified_at')->where(['id'=>$request->id])->first();
			
			if($getUser) {
                if(!Hash::check(trim($request->password),$getUser->password)) {
                    $data=array('status'=>0,'message'=>'Password not matched','data'=> array());
                } else { 
					
					$getUser->remember_token=Hash::make($request->email);
					
					$finalData=array('auth_key'=>$getUser->remember_token);
					
					$getUser->password = Hash::make($request->password_new);

					$getUser->save();
					$data=array('status'=>1,'message'=>'Password Updated Successfully');
								
                }  
            } else {
                $data=array('status'=>0,'message'=>"You don't have an account.Please signup.",'data'=> array());
            }                       
            return response(json_encode($data),200);
        }        
    }
}
