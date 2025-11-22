<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenderAccount extends Model
{
    protected $table = 'vender_account';

    protected $fillable = [
        'user_id',
        'tin',
        'house_number',
        'street',
        'town',
        'city',
        'province',
        'country',
        'altenative_number',
        'bank_name',
        'bank_number', 
        'percentage',
        'work'
    ];

}
