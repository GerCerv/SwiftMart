<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'vendor_id',
        'product_code',
        'name',
        'description',
        'category',
        'price',
        'stock',
        'discount',
        'image',
        'image2',
        'image3',
        'image4',
        'image5',
        'status'
    ];
    protected $casts = [
        'images' => 'array',
        'variants' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            // Auto-generate product code if empty
            if (empty($product->product_code)) {
                $product->product_code = 'PROD-' . Str::upper(Str::random(6));
            }
        });
    }
    
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function images()
    {
        return $this->hasMany(Pimage::class, 'product_id');
    }
    //wishlist
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    //rating
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating') ?: 0;
    }

    public function userRating($userId)
    {
        return $this->ratings()->where('user_id', $userId)->first();
    }

    public function soldCount()
    {
        return OrderItem::where('product_id', $this->id)
            ->where('status', 'delivered')
            ->count();
    }
    //may 15
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    // may 21
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    public function hotDeal()
{
    return $this->hasOne(HotDeal::class)
        ->where('is_active', true)
        ->whereDate('start_date', '<=', now())
        ->whereDate('end_date', '>=', now());
}
}
