<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Please click the button below to verify your email address.')
                    ->action('Verify Email', route('vendor.verify', ['id' => $notifiable->vendor_id, 'hash' => sha1($notifiable->email)]))
                    ->line('Thank you for joining our marketplace!');
    }
}
