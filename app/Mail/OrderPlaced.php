<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $orderItems;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        // Eager load the order items with their products
        $this->orderItems = $order->items()->with('product')->get();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Order #' . $this->order->id . ' Has Been Placed')
                    ->view('emails.order_placed')
                    ->with([
                        'order' => $this->order,
                        'items' => $this->orderItems,
                    ]);
    }
}