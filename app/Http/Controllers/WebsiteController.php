<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;
use Validator;

class WebsiteController extends Controller
{
    public function welcome()
    {
	    // return view('welcome');
		return redirect('/login');
    }

    public function setPassword($email) {
    	$arr=array('XYZ@qdegrees.com',
		'surender.rawat@isonxperiences.com',
		'rooniksingh490@gmail.com',
		'PS00562889@TechMahindra.com',
		'rooby.kashyap@teamhgs.com',
		'ramprabhu.shinde@ruralshores.com',
		'bijay.jay@vodafoneidea.com',
		'Raman.Chatterjee@vodafoneidea.com',
		'Palanikumar.s@vodafoneidea.com',
		'Bhaghyashri.b@vodafoneidea.com',
		'mohammad.munabber@ruralshores.com',
		'santosh.Chaurasiya@aegisglobal.com',
		'AY00470992@TechMahindra.com',
		'munindra.sapia@ruralshores.com',
		'Sonal.Tote@vodafoneidea.com','Jaya.Shaikh@vodafoneidea.com','Rupali.Maniar@vodafoneidea.com',
		'Ruchi.Mohabe@vodafoneidea.com',
		'shweta.jha@vodafoneidea.com',
		'sweta.jha@vodafoneidea.com',
		'shankar.mc@vodafoneidea.com',
		'Shivali.Patel@vodafoneidea.com',
		'priya.ashok@vodafoneidea.com',
		'Mansi.Atoliya@qdegrees.com',
		'sylvia.choudhury@vodafoneidea.com',
		'vanita.almeida@vodafoneidea.com',
		'nishita.malik@vodafoneidea.com',
		'mahima.bajpai@vodafoneidea.com',
		'dineshdavidson.J@vodafoneidea.com',
		'ilina.banerjee7@vodafoneidea.com',
		'rupali.maniar1@vodafoneidea.com','atul.singh@qdegrees.com',
		'samarjeet.singh@qdegrees.com',
		'mohd.azharuddin@qdegrees.com'
		);

    	$userEmail=base64_decode($email);
    	if(in_array($userEmail, $arr)) {
    		return redirect('http://simpliq.qdegrees.com/');
    	} else {
    		$user=User::where('email',$userEmail)->first();
    		return view('password.set_password',compact('user'));
    	}
    	
    }

    public function updatePassword(Request $request) {
    	$validator = Validator::make($request->all(), [    'password' => 'required|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'            
        ]);

        if($validator->fails())
        {
                return redirect('setPassword/'.base64_encode($request->email))
                        ->withErrors($validator)
                        ->withInput();
        } else {
        	//echo "<pre>"; print_r($request->all()); die;
        	$data=User::where('email',$request->email)->first();
        	$data->password = bcrypt($request->password);
        	$data->is_first_time_user=0;
        	$data->save();
        	return redirect('/login')->with('success', 'Password created, Please Login');

        }
    }

    public function sendPasswordResetMail(Request $request)
    {
    	//echo "<pre>"; print_r($request->all()); die;
    	$data=User::where('email',$request->email)->first();
    	if($data) {
    		$mail_data = ['name'=>$data->name,'url'=>url('/setPassword/'.base64_encode($data->email)),'email'=>$data->email,'password'=>$request->password];
	        Mail::to($data->email)->send(new PasswordResetMail($mail_data));
	        return redirect('/login')->with('success', 'Password Reset Mail is successfully sent on your registered email id.');
    	} else {
    		return redirect('/login')->with('success', 'Please use registered email id to reset your password.');
    	}
    	
    }
}
