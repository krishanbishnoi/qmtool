<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class quality_dashboard extends Mailable
{
    use Queueable, SerializesModels;

    public $pdfContent;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pdfContent )
    {
        $this->pdfContent = $pdfContent;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.charts')->subject('Combined Process fortnight  LOB wise Summary update ')
            ->attachData($this->pdfContent, 'BFL-QualityDashboard.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
