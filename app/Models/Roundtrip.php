<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roundtrip extends Model
{
    //use HasFactory;

    protected $table = 'roundtrip';

    protected $fillable = [
        'key',
        'data',
    ];

    protected $casts = [
        'data' => 'array', // Laravel auto json_decode/encode
    ];
}
