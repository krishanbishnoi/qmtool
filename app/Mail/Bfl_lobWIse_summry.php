<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class Bfl_lobWIse_summry extends Mailable
{
    use Queueable, SerializesModels;


    public $locationData;
    public  $pdfContent;
    public  $cumulativePercentages;





    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pdfContent , array $locationData ,  $cumulativePercentages )
    {
        $this->locationData = $locationData;
        $this->pdfContent = $pdfContent;
        $this->cumulativePercentages = $cumulativePercentages;


    }

    /**
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        return $this->view('mail.BFL_sla_managerreport_Summary')->subject('Combined Process fortnight  LOB wise Summary update ')
            ->attachData($this->pdfContent, 'BFL_Lobwise_Summary.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
