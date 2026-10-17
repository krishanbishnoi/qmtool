<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CalibrationRequestNoti extends Notification
{
    use Queueable;

    private $noti_data;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Array $noti_data)
    {
        $this->noti_data = $noti_data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // return (new MailMessage)->markdown('mail.calibration_request');
    }

    public function toDatabase($notifiable)
    {
        return [
            "url"=>"calibration/request",
            "upper_text"=>$this->noti_data['upper_text'],
            "lower_text"=>$this->noti_data['lower_text']
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
