<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;
    protected $table = "stend";

    public function bus()
    {
        return $this->belongsTo(bus::class);
    }
}
