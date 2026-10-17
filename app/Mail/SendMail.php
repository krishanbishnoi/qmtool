<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Smpt;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //$smtps = Stmp::all();
        /* config('MAIL_DRIVER', 'smtp');
        config('MAIL_USERNAME', 'shailendrakumars95@gmail.com');
        config('MAIL_HOST', 'smtp.gmail.com');
        config('MAIL_PASSWORD', 'dirty123rbsk');
        config('MAIL_PORT', '587');
        config('MAIL_ENCRYPTION', 'tls'); */

        return $this->from('shailendrakumars95@gmail.com')->subject('New Customer Equiry')->view('mail.demo-template')->with('data',"hello");
       // return $this->view('view.name');
    }
}
