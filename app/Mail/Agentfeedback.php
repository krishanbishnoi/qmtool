<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Agentfeedback extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $attachments;

    /**
     * Create a new message instance.
     *
     * @return void
     */
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

        // $file = $this->data['Agent_feedback_report'];
        //  return $this->markdown('mail.agent_feedback_Report')->subject("Auditor wise Daily Productivity")->attach($file);
    }
}
