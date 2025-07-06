<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Advertisement extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_path',
        'start_date',
        'expiration_date',
        'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'expiration_date' => 'datetime',
    ];


    public static function getActiveAds()
    {
        // Expire ads where expiration_date is less than or equal to today
        self::whereDate('expiration_date', '<=', now()->toDateString())
            ->where('status', 'active')
            ->update(['status' => 'expired']);

        return self::where('status', 'active')->get();
    }

    public static function getActiveAdsQuery()
{
    // Expire ads where expiration_date is less than or equal to today
    self::whereDate('expiration_date', '<=', now()->toDateString())
        ->where('status', 'active')
        ->update(['status' => 'expired']);

    return self::where('status', 'active');
}


    // Image URL accessor
    public function getImageUrlAttribute()
    {
        return $this->image_path ? Storage::url($this->image_path) : null;
    }
}