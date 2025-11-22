<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    //use HasFactory;

    protected $table = "points";

    protected $fillable = [
        'bus_id',
        'route_id',
        'point_mode',
        'point',
        'state', 
        'amount',
    ];

    public function bus()
    {
        return $this->belongsTo(bus::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'point', 'id');
    }
}
