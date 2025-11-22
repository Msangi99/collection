<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminTransaction extends Model
{
    //use HasFactory;
    protected $table = 'admin_transactions';
    protected $fillable = [
        'trans_ref_id',
        'amount',
        'payment_number',
        'payment_method'
    ];
}
