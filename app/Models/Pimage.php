<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pimage extends Model
{
    //
    protected $fillable = [
        'product_id',
        'vendor_id',
        'image',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'vendor_id');
    }
}
