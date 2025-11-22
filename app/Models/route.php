<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class route extends Model
{
    //use HasFactory;
    protected $table = "routes";
    protected $fillable = ['bus_id', 'from', 'to', 'route_start', 'route_end', 'price', 'distance'];

    public function campany()
    {
        return $this->belongsTo(bus::class);
    }

    public function points()
    {
        return $this->hasMany(Point::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class, 'bus_id', 'id');
    }

    public function via()
    {
        return $this->hasOne(Via::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function schedule()
    {
        return $this->hasOne(Schedule::class, 'route_id', 'id');
    }
}
