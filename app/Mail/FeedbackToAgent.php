<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FeedbackToAgent extends Mailable
{
    use Queueable, SerializesModels;

    Public $data;
    Public $response;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Array $data)
    {
        $this->data = $data;
        $this->response = $data['response'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        $subject = $this->data['subject'];

        // print_r($this->response);
        // die;
        //echo $subject->subject;
        //dd();
        return $this->markdown('mail.agent_feedback')->subject($subject);
    }
}