<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SpecialHireOrder extends Model
{
    use HasFactory;

    protected $table = 'special_hire_orders';

    protected $fillable = [
        'order_code',
        'user_id',
        'coaster_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'pickup_location',
        'pickup_latitude',
        'pickup_longitude',
        'dropoff_location',
        'dropoff_latitude',
        'dropoff_longitude',
        'hire_date',
        'hire_time',
        'return_date',
        'return_time',
        'purpose',
        'passengers_count',
        'distance_km',
        'base_price',
        'price_per_km',
        'km_amount',
        'surcharge_percent',
        'surcharge_amount',
        'total_amount',
        'payment_method',
        'payment_status',
        'order_status',
        'notes',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'return_date' => 'date',
        'distance_km' => 'decimal:2',
        'base_price' => 'decimal:2',
        'price_per_km' => 'decimal:2',
        'km_amount' => 'decimal:2',
        'surcharge_percent' => 'decimal:2',
        'surcharge_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_code)) {
                $order->order_code = self::generateOrderCode();
            }
        });
    }

    /**
     * Generate unique order code.
     */
    public static function generateOrderCode()
    {
        $date = Carbon::now()->format('Ymd');
        $count = self::whereDate('created_at', Carbon::today())->count() + 1;
        return 'SH-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get the user (special_hire owner) that owns this order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the coaster for this order.
     */
    public function coaster()
    {
        return $this->belongsTo(Coaster::class);
    }

    /**
     * Scope to get orders by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get pending orders.
     */
    public function scopePending($query)
    {
        return $query->where('order_status', 'pending');
    }

    /**
     * Scope to get confirmed orders.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('order_status', 'confirmed');
    }

    /**
     * Scope to get completed orders.
     */
    public function scopeCompleted($query)
    {
        return $query->where('order_status', 'completed');
    }

    /**
     * Scope to get today's orders.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('hire_date', Carbon::today());
    }

    /**
     * Scope to get paid orders.
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Check if order is pending.
     */
    public function isPending()
    {
        return $this->order_status === 'pending';
    }

    /**
     * Check if order is paid.
     */
    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Get status badge color.
     */
    public function getStatusColor()
    {
        return match($this->order_status) {
            'pending' => 'yellow',
            'confirmed' => 'blue',
            'in_progress' => 'purple',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get payment status badge color.
     */
    public function getPaymentStatusColor()
    {
        return match($this->payment_status) {
            'pending' => 'yellow',
            'paid' => 'green',
            'refunded' => 'red',
            default => 'gray',
        };
    }
}

