<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeclinedOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'vendor_id',
        'product_id',
        'delivery_man_id',
        'reason',
        'status'
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id'); // or Vendor::class
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function deliveryMan()
    {
        return $this->belongsTo(DeliveryMan::class);
    }
}