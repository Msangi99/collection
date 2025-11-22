<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cancel extends Model
{
    //use HasFactory;
    protected $table = "cancel_bookings";
    protected $fillable = [
        'booking_id', 
        'cancel_reason', 
        'used',
    ];
}
