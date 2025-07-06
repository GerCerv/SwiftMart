<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeIn extends Model
{
    use HasFactory;

    protected $table = 'timein';
    protected $fillable = ['user_id', 'time_in'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


