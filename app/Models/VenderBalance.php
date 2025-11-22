<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenderBalance extends Model
{
    //use HasFactory;

    protected $table = 'vender_balances';

    protected $fillable = [
        'user_id',
        'amount',
        'fees',
        'payment_number',
    ];

    
    public function user()
    {
        return $this->hasOne(User::class);
    }
}
