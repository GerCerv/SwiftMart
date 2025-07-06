<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    // app/Models/Complaint.php
    protected $fillable = [
        'user_id',
        'product_id',
        'store_name',
        'message'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
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
        return $this->belongsTo(User::class, 'vendor_id');
    }

    
}