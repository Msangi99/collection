<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    //use HasFactory;

    protected $table = 'discount';

    protected $fillable = [
        'code',
        'used',
        'percentage'
    ];

    public function booking()
    {
        return $this->hasMany(Booking::class, 'discount', 'code');
    }
}
