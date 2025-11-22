<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundPercentage extends Model
{
    //use HasFactory;

    protected $table = "refund_percentages";

    protected $fillable = [
        'booking_code',
        'amount',
    ];
}
