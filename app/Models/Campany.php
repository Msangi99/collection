<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campany extends Model
{
    //use HasFactory;
    protected $table = "campanies";
    protected $fillable = [
        'name',
        'user_id',
        'payment_number',
        'status',
        'percentage',
    ];

    public function bus()
    {
        return $this->hasMany(bus::class, 'campany_id', 'id');
    }

    public function buses()
    {
        return $this->belongsTo(bus::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function balance()
    {
        return $this->hasOne(balance::class);
    }

    public function busOwnerAccount()
    {
        return $this->hasOne(BusOwnerAccount::class, 'campany_id', 'id');
    }
}
