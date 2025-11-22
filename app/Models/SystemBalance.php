<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemBalance extends Model
{
    //use HasFactory;

    protected $table = 'system_balance';
    protected $fillable = [
        'campany_id',
        'balance',
    ];

    public function campany()
    {
        return $this->belongsTo(Campany::class);
    }
}
