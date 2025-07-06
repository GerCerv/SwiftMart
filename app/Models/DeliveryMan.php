<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class DeliveryMan extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'profile_image', 'is_active'
    ];
    protected $hidden = ['password', 'remember_token'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'delivery_man_id');
    }
}