<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancelledBookings extends Model
{
    //use HasFactory;
    protected $table = 'cancelled_bookings';
    protected $fillable = [
        'booking_id',
        'amount',
        'campany_id'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function campany()
    {
        return $this->belongsTo(Campany::class, 'campany_id');
    }
}
