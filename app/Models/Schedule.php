<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_id',
        'route_id',
        'from',
        'to',
        'schedule_date',
        'start',
        'end',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function parentSchedule()
    {
        return $this->belongsTo(Schedule::class, 'parent_schedule_id');
    }

    public function childSchedule()
    {
        return $this->hasOne(Schedule::class, 'parent_schedule_id');
    }
}
