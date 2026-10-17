<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RaisedRebuttal extends Mailable
{
    use Queueable, SerializesModels;

    Public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        $subject = $this->data['subject'];
        //echo $subject->subject;
        //dd();
        return $this->markdown('mail.raised_rebuttal')->subject($subject);
    }
}
