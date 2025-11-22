<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusOwnerAccount extends Model
{
    protected $table = 'bus_owner_account';

    protected $fillable = [
        'user_id',
        'registration_number',
        'tin',
        'vrn',
        'office_number',
        'box',
        'street',
        'town',
        'city',
        'region',
        'country',
        'bank_number',
        'bank_name',
        'whatsapp_number',
    ];

}
