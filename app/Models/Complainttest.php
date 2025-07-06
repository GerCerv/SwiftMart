<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complainttest extends Model
{
    protected $fillable = ['user_id', 'order_item_id', 'complaint_type'];
}
