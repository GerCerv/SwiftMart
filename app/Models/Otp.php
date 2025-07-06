<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
   protected $fillable = ['user_id', 'otp', 'expires_at'];

   protected $dates = ['expires_at'];
}
