<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notification;

class Vendor extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'vendor_id';  // This tells Laravel the primary key is 'vendor_id'
    
    protected $fillable = [
        'name', 'store_name', 'email', 'phone', 'password', 'status', 'verification_token'
    ];

    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\VerifyEmailNotification);
    }
    public function isVerifiedAndApproved()
    {
        return $this->hasVerifiedEmail() && $this->status === 'approved';
    }
    //connection
    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id'); // Specify the correct foreign key here
    }

    //may7
    // Relationship to delivered orders through order items
    public function deliveredOrders()
    {
        return $this->hasMany(OrderItem::class, 'vendor_id', 'vendor_id')
            ->whereHas('order', function($query) {
                $query->where('status', 'Delivered');
            });
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'vendor_id'); // Adjust if 'vendor_id' is not the foreign key
    }



    public function getVendorRatingAttribute()
{
    // Get all products with ratings and calculate the average of their average ratings
    $productRatings = $this->products
        ->filter(function ($product) {
            return $product->ratings()->count() > 0; // Only include products with ratings
        })
        ->map(function ($product) {
            return $product->averageRating() ?? 0; // Average rating of rated products
        })
        ->avg();

    return round($productRatings, 1) ?? 0; // Round to 1 decimal place, default to 0 if no rated products
}

    public function stallSettings()
    {
        return $this->hasOne(StallSetting::class, 'vendor_id');
    }
}
