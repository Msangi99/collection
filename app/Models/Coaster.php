<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coaster extends Model
{
    use HasFactory;

    protected $table = 'coasters';

    protected $fillable = [
        'user_id',
        'name',
        'plate_number',
        'capacity',
        'model',
        'color',
        'status',
        'image',
        'driver_name',
        'driver_contact',
        'latitude',
        'longitude',
        'last_location_update',
        'features',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'last_location_update' => 'datetime',
    ];

    /**
     * Get the user (special_hire owner) that owns the coaster.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the orders for this coaster.
     */
    public function orders()
    {
        return $this->hasMany(SpecialHireOrder::class);
    }

    /**
     * Get the pricing for this coaster.
     */
    public function pricing()
    {
        return $this->hasOne(SpecialHirePricing::class);
    }

    /**
     * Scope to get available coasters.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope to get coasters by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Check if coaster is available.
     */
    public function isAvailable()
    {
        return $this->status === 'available';
    }

    /**
     * Check if coaster is on hire.
     */
    public function isOnHire()
    {
        return $this->status === 'on_hire';
    }

    /**
     * Update coaster location.
     */
    public function updateLocation($latitude, $longitude)
    {
        $this->update([
            'latitude' => $latitude,
            'longitude' => $longitude,
            'last_location_update' => now(),
        ]);
    }
}

