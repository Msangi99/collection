<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminWallet extends Model
{
    protected $table = 'admin_wallet';

    protected $fillable = [
        'service_balance',
        'commision_balance',
        'balance',
        'vat'
    ];
}
