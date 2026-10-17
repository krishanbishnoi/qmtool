<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ClientWiseProductivityReport  extends Mailable
{
    use Queueable, SerializesModels;
    public $test;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Array $test)
    {
        $this->test= $test;
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->test['subject'];
        $file = $this->test['file'];
        return $this->markdown('mail.client_wise_productivity_report')->subject($subject)->attach($file);
        // return $this->view('view.name');
       
    }
}
