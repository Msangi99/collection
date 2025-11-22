<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentFees extends Model
{
    use HasFactory;
    protected $table = 'payment_fees';

    protected $fillable = [
        'campany_id',
        'booking_id',
        'amount'
    ];

    public function campany()
    {
        return $this->belongsTo(Campany::class, 'campany_id');
    }

}
