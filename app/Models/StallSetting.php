<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StallSetting extends Model
{
    protected $fillable = [
        'vendor_id',
        'profile_image',
        'background_image',
        'facebook',
        'instagram',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}