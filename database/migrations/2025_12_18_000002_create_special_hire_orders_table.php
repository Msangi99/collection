<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('special_hire_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code', 20)->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('coaster_id')->constrained()->onDelete('cascade');
            
            // Customer info
            $table->string('customer_name', 100);
            $table->string('customer_phone', 20);
            $table->string('customer_email', 100)->nullable();
            
            // Pickup location
            $table->string('pickup_location', 255);
            $table->decimal('pickup_latitude', 10, 8)->nullable();
            $table->decimal('pickup_longitude', 11, 8)->nullable();
            
            // Dropoff location
            $table->string('dropoff_location', 255);
            $table->decimal('dropoff_latitude', 10, 8)->nullable();
            $table->decimal('dropoff_longitude', 11, 8)->nullable();
            
            // Schedule
            $table->date('hire_date');
            $table->time('hire_time');
            $table->date('return_date')->nullable();
            $table->time('return_time')->nullable();
            
            // Trip details
            $table->string('purpose', 255)->nullable();
            $table->integer('passengers_count')->default(1);
            
            // Pricing (KM-based)
            $table->decimal('distance_km', 10, 2)->default(0);
            $table->decimal('base_price', 12, 2)->default(0);
            $table->decimal('price_per_km', 10, 2)->default(0);
            $table->decimal('km_amount', 12, 2)->default(0);
            $table->decimal('surcharge_percent', 5, 2)->default(0);
            $table->decimal('surcharge_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            
            // Payment
            $table->string('payment_method', 50)->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
            
            // Order status
            $table->enum('order_status', ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'])->default('pending');
            
            // Notes
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('special_hire_orders');
    }
};

