<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSetting extends Model
{
    protected $fillable = [
        'carousel_items',
        'top_vendors',
        'hot_deals',
        'discounts',
        'fresh_picks',
        'promotional_section',
        'discount_banner',
    ];

    protected $casts = [
        'carousel_items' => 'array',
        'top_vendors' => 'array',
        'hot_deals' => 'array',
        'discounts' => 'array',
        'fresh_picks' => 'array',
        'promotional_section' => 'array',
        'discount_banner' => 'array',
    ];
}
