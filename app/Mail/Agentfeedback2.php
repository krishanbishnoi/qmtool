<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Agentfeedback2 extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

     public $subject;
     public $attachments;
 


     public function __construct($subject, $attachments)
     {
         $this->subject = $subject;
         $this->attachments = $attachments;
     }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $subject = $this->subject;
        $attachments = $this->attachments;


        return $this->subject($subject)->markdown('mail.agent_feedback_Report')
            ->with(['attachments' => $attachments]);      
    }
}
