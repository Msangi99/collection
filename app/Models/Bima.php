<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bima extends Model
{
    //use HasFactory;
    protected $table = 'bima';
    protected $fillable = [
        'booking_id',
        'start_date',
        'end_date',
        'amount',
        'bima_vat'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
