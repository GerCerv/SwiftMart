<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ItemDeclinedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $item;
    protected $reason;

    public function __construct($item, $reason)
    {
        $this->item = $item;
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Order Item Declined')
            ->line('One of your order items has been declined by the delivery personnel.')
            ->line('Product: ' . $this->item->product->name)
            ->line('Reason: ' . $this->reason)
            ->action('View Details', url('/vendor/orders'))
            ->line('Thank you for using our service!');
    }
}