<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class tata_productivity extends Mailable
{
    use Queueable, SerializesModels;

    public  $filePath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($filePath )
    {
        $this->filePath = $filePath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.bajaj_finance_productivity')->subject("TATA Auditor wise Daily Productivity")->attach($this->filePath);;
    }
}
