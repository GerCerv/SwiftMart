<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $status;
    public $orderItems;

    public function __construct(Order $order, $status)
    {
        $this->order = $order;
        $this->status = $status;
        $this->orderItems = $order->items()->with('product')->get();
    }

    public function build()
    {
        return $this->subject('Your Order #' . $this->order->id . ' Status Updated')
                    ->view('emails.order_status_updated')
                    ->with([
                        'order' => $this->order,
                        'status' => $this->status,
                        'items' => $this->orderItems,
                    ]);
    }
}