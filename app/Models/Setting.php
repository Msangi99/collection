<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    
    protected $fillable = 
        [
            'international',
            'local',
            'service',
            'service_percentage',
            'enable_customer_sms_notifications',
            'enable_customer_email_notifications',
            'enable_conductor_sms_notifications',
            'enable_conductor_email_notifications',
        ];
}