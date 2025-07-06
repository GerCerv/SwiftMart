<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'vendor_id',
        'quantity',
        'pack_size',
        'unit_price',
        'discount',
        'total_price',
        'delivery_man_id',
        'status',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function deliveryMan()
    {
        return $this->belongsTo(DeliveryMan::class);
    }
    public function declinedOrderItem()
    {
        return $this->hasOne(DeclinedOrderItem::class, 'order_item_id');
    }
}