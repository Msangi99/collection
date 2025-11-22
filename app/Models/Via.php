<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Via extends Model
{
    //use HasFactory;

    protected $table = 'via';
    protected $fillable = [
        'bus_id',
        'route_id',
        'name',
    ];

    public function route()
    {
        return $this->belongsTo(route::class);
    }
}
