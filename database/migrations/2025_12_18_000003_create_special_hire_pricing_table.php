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
        Schema::create('special_hire_pricing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coaster_id')->constrained()->onDelete('cascade');
            $table->decimal('base_price', 12, 2)->default(100000);
            $table->decimal('price_per_km', 10, 2)->default(2500);
            $table->integer('min_km')->default(10);
            $table->decimal('weekend_surcharge_percent', 5, 2)->default(15);
            $table->decimal('night_surcharge_percent', 5, 2)->default(20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('special_hire_pricing');
    }
};

