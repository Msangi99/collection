<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempWallet extends Model
{
    //use HasFactory;
    protected $table = 'temp_wallets';
    protected $fillable = [
        'user_id',
        'user_key',
        'amount',
        'status'
    ];
}
