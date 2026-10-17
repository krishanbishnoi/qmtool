<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class bajajauditreport extends Mailable
{
    use Queueable, SerializesModels;

    // public $test;

    public  $filePath;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($filePath )
    {
        // $this->test= $test;
        $this->filePath = $filePath;

          }



    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // $subject = $this->test['subject'];
        
         return $this->markdown('mail.bajaj_finance_productivity')->subject("Bajaj Auditor wise Daily Productivity")->attach($this->filePath);;
        //  return $this->view('mail.bajaj_finance_productivity');
    }
}
