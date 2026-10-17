<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class HourlyReportData extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $attachments;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $attachments)
    {
        $this->data = $data;
        $this->attachments = $attachments;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    // public function build()
    // {
    //     $email = $this->subject('Hourly Audit Reports')
    //                   ->view('mail.hourly_working_report');

    //     foreach ($this->attachments as $filePath) {
    //         $email->attach($filePath);
    //     }

    //     return $email;
    // }

    public function build()
    {
       
        $attachments = $this->attachments;

        return $this->markdown('mail.hourly_working_report')
            ->with(['attachments' => $attachments]);

    }
}
