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
        Schema::create('coasters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name', 100);
            $table->string('plate_number', 20)->unique();
            $table->integer('capacity')->default(30);
            $table->string('model', 100)->nullable();
            $table->string('color', 50)->nullable();
            $table->enum('status', ['available', 'on_hire', 'maintenance'])->default('available');
            $table->string('image')->nullable();
            $table->string('driver_name', 100)->nullable();
            $table->string('driver_contact', 20)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamp('last_location_update')->nullable();
            $table->text('features')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coasters');
    }
};

