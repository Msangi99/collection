<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    //use HasFactory;

    protected $table = "bookings";

    protected $fillable = [
        'booking_code',
        'campany_id',
        'bus_id',
        'route_id',
        'pickup_point',
        'dropping_point',
        'travel_date',
        'seat',
        'amount',
        'payment_method',
        'payment_status',
        'resaved_until',
        'trans_status',
        'transaction_ref_id',
        'external_ref_id',
        'gender',
        'age',
        'infant_child',
        'age_group',
        'mfs_id',
        'verification_code',
        ////////
        'customer_phone',
        'customer_name',
        'customer_email',
        'user_id',
        'bima',
        'bima_amount',
        'insuranceDate',
        'vender_id',
        'fee',
        'service',
        //////
        'vender_fee',
        'vender_service',
        'schedule_id',
        'vat',
        'discount',
        'discount_amount',
        'distance',
        'busFee',
    ];

    public function bus()
    {
        return $this->belongsTo(bus::class);
    }

    public function route()
    {
        return $this->belongsTo(route::class);
    }
 
    public function route_name()
    {
        return $this->hasOne(route::class, 'id', 'route_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function campany()
    {
        return $this->hasOne(campany::class, 'id', 'campany_id');
    }

    public function vender()
    {
        return $this->hasOne(User::class, 'id', 'vender_id');
    }

    public function discounta()
    {
        return $this->hasOne(Discount::class, 'code', 'discount');
    }
}
