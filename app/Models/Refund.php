<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $table = "refund";
    protected $fillable = [
        'booking_code',
        'amount',
        'status',
        'phone',
        'fullname'
    ];
}
