<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use Mail;

class ApplyTrainingController extends Controller
{
    public function apply_training(Request $request){
        return view('partner_qa.apply_training');
    }
    public function apply_training_submit(Request $request){

        
       $feedback_data = $request->apply_traning;
       $assigned_sme = Auth::user()->assigned_sme;
       $sme_email = User::find($assigned_sme)->email;

        Mail::send('partner_qa.traning_report',['feedback_data' => $feedback_data],function($message) use ($sme_email){
            $message->from('qaviews-noreply@qdegrees.com','Qdegrees')->to($sme_email)->subject("Request for feedback");
        });
        return  redirect()->back()->with('success', 'Request send successfully.!');

    }
}


