<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ItemDeclinedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $productName;
    public $reason;
    public $deliveryManName;

    public function __construct($productName, $reason, $deliveryManName)
    {
        $this->productName = $productName;
        $this->reason = $reason;
        $this->deliveryManName = $deliveryManName;
    }

    public function build()
    {
        return $this->subject('Your Item Has Been Declined')
                    ->view('emails.item_declined');
    }
}